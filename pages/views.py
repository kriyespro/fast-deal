from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.utils.decorators import method_decorator
from django.views import View


class PageView(View):
    template_name = None

    def get(self, request, *args, **kwargs):
        return render(request, self.template_name, self.get_context(request, **kwargs))

    def get_context(self, request, **kwargs):
        return {}


class HomeView(PageView):
    template_name = 'pages/index.jinja'

    def get_context(self, request, **kwargs):
        from properties.services import get_featured_properties
        return {'featured_properties': get_featured_properties(limit=5)}


class ListingsView(PageView):
    template_name = 'pages/listings.jinja'


class PropertyDetailView(PageView):
    template_name = 'pages/property_detail.jinja'


class AgentsView(PageView):
    template_name = 'pages/agents.jinja'


class AgentDetailView(PageView):
    template_name = 'pages/agent_detail.jinja'


class NeighborhoodsView(PageView):
    template_name = 'pages/neighborhoods.jinja'


class AboutView(PageView):
    template_name = 'pages/about.jinja'


class ContactView(PageView):
    template_name = 'pages/contact.jinja'


class BlogView(PageView):
    template_name = 'pages/blog.jinja'


class BlogDetailView(PageView):
    template_name = 'pages/blog_detail.jinja'


def _require_role(user, *roles):
    from users.models import Role
    from django.core.exceptions import PermissionDenied
    allowed = set(roles)
    if user.role not in allowed:
        # Global admin may view any dashboard for support
        if user.role != Role.GLOBAL_ADMIN:
            raise PermissionDenied


@method_decorator(login_required, name='dispatch')
class DashboardClientView(View):
    template_name = 'pages/dashboard_client.jinja'

    def get(self, request):
        from users.models import Role
        _require_role(request.user, Role.CLIENT)
        from properties.models import Inquiry
        from properties.services import get_client_saved_properties
        my_inquiries = Inquiry.objects.filter(
            client=request.user
        ).select_related('property', 'property__broker', 'property__city').order_by('-created_at')[:20]
        saved_properties = get_client_saved_properties(request.user)
        return render(request, self.template_name, {
            'my_inquiries': my_inquiries,
            'saved_properties': saved_properties,
        })


@method_decorator(login_required, name='dispatch')
class DashboardBrokerView(View):
    template_name = 'pages/dashboard_broker.jinja'

    def get(self, request):
        from users.models import Role
        _require_role(request.user, Role.BROKER)
        from properties.services import get_broker_properties, get_broker_stats
        from properties.models import Inquiry
        from billing.services import can_add_listing, get_user_active_plan
        properties = get_broker_properties(request.user)
        stats = get_broker_stats(request.user)
        inquiries = Inquiry.objects.filter(
            property__broker=request.user
        ).select_related('property').order_by('-created_at')[:20]
        can_list, listing_limit = can_add_listing(request.user)
        active_plan = get_user_active_plan(request.user)
        return render(request, self.template_name, {
            'properties': properties,
            'stats': stats,
            'inquiries': inquiries,
            'can_add_listing': can_list,
            'listing_limit': listing_limit,
            'active_plan': active_plan,
        })


@method_decorator(login_required, name='dispatch')
class DashboardBuilderView(View):
    template_name = 'pages/dashboard_builder.jinja'

    def get(self, request):
        from users.models import Role
        _require_role(request.user, Role.BUILDER)
        from properties.services import get_builder_projects, get_builder_project_stats
        projects = get_builder_projects(request.user)
        project_stats = get_builder_project_stats(request.user)
        return render(request, self.template_name, {
            'projects': projects,
            'project_stats': project_stats,
        })


@method_decorator(login_required, name='dispatch')
class DashboardAdminView(View):
    template_name = 'pages/dashboard_admin.jinja'

    def get(self, request):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.core.exceptions import PermissionDenied
            raise PermissionDenied

        if request.user.role == Role.GLOBAL_ADMIN:
            return redirect('control:dashboard')

        context = {'admin_role': 'city'}

        if request.user.role == Role.CITY_ADMIN:
            profile = getattr(request.user, 'profile', None)
            city = profile.assigned_city if profile else None
            if city:
                from properties.services import (
                    get_city_stats, get_city_pending_properties,
                    get_city_all_listings, get_city_brokers,
                )
                context.update({
                    'city': city,
                    'city_stats': get_city_stats(city),
                    'pending_properties': get_city_pending_properties(city),
                    'all_listings': get_city_all_listings(city),
                    'city_brokers': get_city_brokers(city),
                })
            else:
                context['no_city'] = True

        return render(request, self.template_name, context)


@method_decorator(login_required, name='dispatch')
class ApproveListingView(View):
    def post(self, request, pk):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.http import HttpResponseForbidden
            return HttpResponseForbidden()
        from properties.services import approve_property
        prop = approve_property(pk)
        return render(request, 'partials/_city_listing_row.jinja', {'property': prop})


@method_decorator(login_required, name='dispatch')
class RejectListingView(View):
    def post(self, request, pk):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.http import HttpResponseForbidden
            return HttpResponseForbidden()
        from properties.services import reject_property
        prop = reject_property(pk)
        return render(request, 'partials/_city_listing_row.jinja', {'property': prop})


@method_decorator(login_required, name='dispatch')
class VerifyBrokerView(View):
    def post(self, request, pk):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.http import HttpResponseForbidden
            return HttpResponseForbidden()
        from properties.services import verify_broker
        broker = verify_broker(pk)
        return render(request, 'partials/_city_broker_row.jinja', {'broker': broker})


@method_decorator(login_required, name='dispatch')
class SuspendBrokerView(View):
    def post(self, request, pk):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.http import HttpResponseForbidden
            return HttpResponseForbidden()
        from properties.services import suspend_broker
        broker = suspend_broker(pk)
        return render(request, 'partials/_city_broker_row.jinja', {'broker': broker})


@method_decorator(login_required, name='dispatch')
class ReinstateBrokerView(View):
    def post(self, request, pk):
        from users.models import Role
        if request.user.role not in (Role.CITY_ADMIN, Role.GLOBAL_ADMIN):
            from django.http import HttpResponseForbidden
            return HttpResponseForbidden()
        from properties.services import reinstate_broker
        broker = reinstate_broker(pk)
        return render(request, 'partials/_city_broker_row.jinja', {'broker': broker})
