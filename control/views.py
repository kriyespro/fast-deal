from django.shortcuts import render, get_object_or_404, redirect
from django.views import View
from django.http import HttpResponseForbidden
from django.contrib.auth import login

from .services import (
    get_platform_stats, get_users_list, get_recent_admin_logs,
    log_admin_action, get_client_ip,
)


class ControlAccessMixin:
    def dispatch(self, request, *args, **kwargs):
        if not request.user.is_authenticated:
            return redirect(f'/login/?next={request.path}')
        if not (request.user.is_staff or request.user.role == 'global_admin'):
            from django.core.exceptions import PermissionDenied
            raise PermissionDenied
        return super().dispatch(request, *args, **kwargs)


class DashboardView(ControlAccessMixin, View):
    def get(self, request):
        stats = get_platform_stats()
        recent_logs = get_recent_admin_logs(20)
        from users.models import User, Role
        recent_users = User.objects.select_related('profile').order_by('-date_joined')[:5]
        return render(request, 'control/dashboard.jinja', {
            'stats': stats,
            'recent_logs': recent_logs,
            'recent_users': recent_users,
        })


class UserListView(ControlAccessMixin, View):
    def get(self, request):
        q = request.GET.get('q', '').strip()
        role = request.GET.get('role', '')
        users = get_users_list(q=q, role=role)
        context = {'users': users, 'q': q, 'selected_role': role}
        if request.htmx:
            return render(request, 'control/partials/_user_row.jinja', context)
        return render(request, 'control/users.jinja', context)


class BanUserView(ControlAccessMixin, View):
    def post(self, request, pk):
        from users.models import User
        user = get_object_or_404(User, pk=pk)
        if user.is_superuser:
            return HttpResponseForbidden('Cannot ban superusers.')
        user.is_active = not user.is_active
        user.save(update_fields=['is_active'])
        action = 'unban_user' if user.is_active else 'ban_user'
        log_admin_action(request.user, action, target_user=user, ip=get_client_ip(request))
        user = User.objects.select_related('profile').get(pk=pk)
        return render(request, 'control/partials/_user_row.jinja', {'users': [user]})


class ImpersonateView(ControlAccessMixin, View):
    def post(self, request, pk):
        if not request.user.is_staff:
            return HttpResponseForbidden()
        from users.models import User
        target = get_object_or_404(User, pk=pk)
        if target.is_superuser:
            return HttpResponseForbidden('Cannot impersonate superusers.')
        request.session['impersonator_id'] = request.user.pk
        log_admin_action(request.user, 'impersonate', target_user=target, ip=get_client_ip(request))
        login(request, target, backend='django.contrib.auth.backends.ModelBackend')
        return redirect('home')


class StopImpersonateView(View):
    def post(self, request):
        impersonator_id = request.session.pop('impersonator_id', None)
        if impersonator_id:
            from users.models import User
            admin_user = get_object_or_404(User, pk=impersonator_id)
            log_admin_action(admin_user, 'stop_impersonate', target_user=request.user)
            login(request, admin_user, backend='django.contrib.auth.backends.ModelBackend')
        return redirect('control:dashboard')


class StatsCardView(ControlAccessMixin, View):
    def get(self, request):
        stats = get_platform_stats()
        return render(request, 'control/partials/_stats_card.jinja', {'stats': stats})


class ActivityFeedView(ControlAccessMixin, View):
    def get(self, request):
        recent_logs = get_recent_admin_logs(20)
        return render(request, 'control/partials/_activity_feed.jinja', {'recent_logs': recent_logs})
