from django.contrib.auth import login, logout
from django.contrib.auth.views import LoginView
from django.shortcuts import redirect, render
from django.views import View
from django.contrib import messages

from .forms import LoginForm, RegisterForm
from .models import Role


ROLE_DASHBOARD_MAP = {
    Role.CLIENT: 'dashboard_client',
    Role.BROKER: 'dashboard_broker',
    Role.BUILDER: 'dashboard_builder',
    Role.CITY_ADMIN: 'dashboard_admin',
    Role.GLOBAL_ADMIN: 'dashboard_admin',
}


class LoginPageView(LoginView):
    template_name = 'pages/login.jinja'
    authentication_form = LoginForm

    def get_success_url(self):
        role = self.request.user.role
        url_name = ROLE_DASHBOARD_MAP.get(role, 'home')
        from django.urls import reverse
        return reverse(url_name)


class RegisterView(View):
    def get(self, request):
        if request.user.is_authenticated:
            return redirect('home')
        form = RegisterForm()
        return render(request, 'pages/register.jinja', {'form': form})

    def post(self, request):
        form = RegisterForm(request.POST)
        if form.is_valid():
            user = form.save()
            login(request, user)
            messages.success(request, f'Welcome, {user.full_name}! Aapka account ban gaya.')
            role = user.role
            url_name = ROLE_DASHBOARD_MAP.get(role, 'home')
            from django.urls import reverse
            return redirect(reverse(url_name))
        return render(request, 'pages/register.jinja', {'form': form})


class LogoutView(View):
    def post(self, request):
        logout(request)
        return redirect('login')
