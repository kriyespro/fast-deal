from django.shortcuts import render, get_object_or_404
from django.views import View
from django.core.paginator import Paginator
from django.http import HttpResponse
from django.contrib.auth.decorators import login_required
from django.utils.decorators import method_decorator

from .models import Property, PropertyStatus, City, SavedProperty, Project
from .services import (
    get_active_properties, get_featured_properties, get_property_detail,
    create_inquiry, increment_views, toggle_saved_property,
    get_builder_projects,
)
from .forms import InquiryForm, PropertyFilterForm, ProjectForm, BuilderProfileForm


class ListingsView(View):
    def get(self, request):
        form = PropertyFilterForm(request.GET)
        filters = {}
        if form.is_valid():
            filters = {k: v for k, v in form.cleaned_data.items() if v}

        qs = get_active_properties(filters)
        paginator = Paginator(qs, 12)
        page = paginator.get_page(request.GET.get('page', 1))

        cities = City.objects.filter(is_active=True)
        saved_ids = set()
        if request.user.is_authenticated:
            saved_ids = set(SavedProperty.objects.filter(user=request.user).values_list('property_id', flat=True))
        context = {
            'properties': page,
            'form': form,
            'cities': cities,
            'total_count': paginator.count,
            'saved_ids': saved_ids,
        }

        # HTMX request → return only the cards partial
        if request.htmx:
            return render(request, 'partials/_property_cards.jinja', context)
        return render(request, 'pages/listings.jinja', context)


class PropertyDetailView(View):
    def get(self, request, slug):
        from django.http import Http404
        try:
            prop = get_property_detail(slug)
        except Property.DoesNotExist:
            try:
                prop = Property.objects.select_related(
                    'city', 'locality', 'broker', 'broker__profile'
                ).prefetch_related('images').get(slug=slug)
                if not (request.user.is_authenticated and prop.broker_id == request.user.pk):
                    raise Http404
            except Property.DoesNotExist:
                raise Http404
        increment_views(prop.pk)
        form = InquiryForm()
        similar = get_active_properties({'city': prop.city.name if prop.city else None}).exclude(pk=prop.pk)[:4]
        is_saved = False
        if request.user.is_authenticated:
            is_saved = SavedProperty.objects.filter(user=request.user, property=prop).exists()
        import json
        images_json = json.dumps([img.image.url for img in prop.images.all()])
        return render(request, 'pages/property_detail.jinja', {
            'property': prop,
            'form': form,
            'similar': similar,
            'is_saved': is_saved,
            'images_json': images_json,
        })

    def post(self, request, slug):
        prop = get_object_or_404(Property, slug=slug, status=PropertyStatus.ACTIVE)
        form = InquiryForm(request.POST)
        if form.is_valid():
            create_inquiry(prop, form.cleaned_data, user=request.user)
            if request.htmx:
                return HttpResponse(
                    '<div class="p-4 bg-green-50 rounded-xl text-green-700 text-sm font-medium border border-green-200">'
                    '✓ Aapki inquiry send ho gayi! Broker jald hi contact karega.</div>'
                )
            from django.shortcuts import redirect
            return redirect('property_detail', slug=slug)

        if request.htmx:
            return render(request, 'partials/_inquiry_form.jinja', {'form': form, 'property': prop})
        return render(request, 'pages/property_detail.jinja', {'property': prop, 'form': form})


@method_decorator(login_required, name='dispatch')
class ToggleSaveView(View):
    def post(self, request, pk):
        saved = toggle_saved_property(request.user, pk)
        return render(request, 'partials/_save_btn.jinja', {
            'property_pk': pk,
            'is_saved': saved,
        })


@method_decorator(login_required, name='dispatch')
class AddProjectView(View):
    def get(self, request):
        from billing.services import can_add_project
        allowed, limit = can_add_project(request.user)
        if not allowed:
            return HttpResponse(
                f'<div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-center">'
                f'<i class="ph-fill ph-lock text-2xl block mb-2 text-amber-500"></i>'
                f'<strong>Project limit reached ({limit} projects on your plan)</strong><br>'
                f'<a href="/billing/" class="mt-3 inline-block bg-orange text-white text-sm font-bold px-5 py-2 rounded-xl">Upgrade Plan</a>'
                f'</div>'
            )
        cities = City.objects.filter(is_active=True)
        form = ProjectForm()
        return render(request, 'partials/_project_form.jinja', {
            'form': form, 'cities': cities,
        })

    def post(self, request):
        from billing.services import can_add_project
        allowed, limit = can_add_project(request.user)
        if not allowed:
            return HttpResponse(
                f'<div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-center">'
                f'<i class="ph-fill ph-lock text-2xl block mb-2 text-amber-500"></i>'
                f'<strong>Project limit reached ({limit} projects on your plan).</strong><br>'
                f'<a href="/billing/" class="mt-3 inline-block bg-orange text-white text-sm font-bold px-5 py-2 rounded-xl">Upgrade Plan</a>'
                f'</div>'
            )
        form = ProjectForm(request.POST, request.FILES)
        if form.is_valid():
            project = form.save(commit=False)
            project.builder = request.user
            project.available_units = project.total_units
            project.save()
            return HttpResponse(
                '<div class="p-5 bg-green-50 border border-green-200 rounded-2xl text-green-700 font-medium text-center">'
                f'<i class="ph-fill ph-check-circle text-2xl block mb-2"></i>'
                f'Project <strong>{project.name}</strong> submitted for review!</div>'
            )
        cities = City.objects.filter(is_active=True)
        return render(request, 'partials/_project_form.jinja', {
            'form': form, 'cities': cities,
        })


@method_decorator(login_required, name='dispatch')
class BuilderProfileView(View):
    def get(self, request):
        profile = request.user.profile
        initial = {
            'full_name': request.user.full_name,
            'phone': request.user.phone,
            'company_name': profile.company_name,
            'rera_number': profile.rera_number,
            'website': profile.website,
            'bio': profile.bio,
        }
        form = BuilderProfileForm(initial=initial)
        return render(request, 'partials/_builder_profile_form.jinja', {'form': form})

    def post(self, request):
        form = BuilderProfileForm(request.POST)
        if form.is_valid():
            d = form.cleaned_data
            user = request.user
            user.full_name = d['full_name']
            user.phone = d.get('phone', '')
            user.save(update_fields=['full_name', 'phone'])
            profile = user.profile
            profile.company_name = d.get('company_name', '')
            profile.rera_number = d.get('rera_number', '')
            profile.website = d.get('website', '')
            profile.bio = d.get('bio', '')
            profile.save()
            return HttpResponse(
                '<div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">'
                '✓ Profile updated successfully!</div>'
            )
        return render(request, 'partials/_builder_profile_form.jinja', {'form': form})
