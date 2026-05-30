from django import forms
from django.contrib.auth.forms import AuthenticationForm
from .models import User, Role


class LoginForm(AuthenticationForm):
    username = forms.EmailField(
        widget=forms.EmailInput(attrs={
            'placeholder': 'aapka@email.com',
            'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
            'autofocus': True,
        }),
        label='Email'
    )
    password = forms.CharField(
        widget=forms.PasswordInput(attrs={
            'placeholder': '••••••••',
            'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
        }),
        label='Password'
    )


ROLE_CHOICES = [
    (Role.CLIENT, 'Client — Property Khareedna / Rent Lena'),
    (Role.BROKER, 'Broker — Properties List Karna'),
    (Role.BUILDER, 'Builder — Projects List Karna'),
]


class RegisterForm(forms.ModelForm):
    password1 = forms.CharField(
        widget=forms.PasswordInput(attrs={
            'placeholder': '••••••••',
            'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
        }),
        label='Password'
    )
    password2 = forms.CharField(
        widget=forms.PasswordInput(attrs={
            'placeholder': '••••••••',
            'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
        }),
        label='Confirm Password'
    )
    role = forms.ChoiceField(
        choices=ROLE_CHOICES,
        widget=forms.RadioSelect(),
        label='Aap kya hain?'
    )

    class Meta:
        model = User
        fields = ('full_name', 'email', 'phone', 'role')
        widgets = {
            'full_name': forms.TextInput(attrs={
                'placeholder': 'Aapka poora naam',
                'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
            }),
            'email': forms.EmailInput(attrs={
                'placeholder': 'aapka@email.com',
                'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
            }),
            'phone': forms.TextInput(attrs={
                'placeholder': '+91 98765 43210',
                'class': 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm',
            }),
        }

    def clean_password2(self):
        p1 = self.cleaned_data.get('password1')
        p2 = self.cleaned_data.get('password2')
        if p1 and p2 and p1 != p2:
            raise forms.ValidationError('Passwords do not match')
        return p2

    def save(self, commit=True):
        user = super().save(commit=False)
        user.set_password(self.cleaned_data['password1'])
        if commit:
            user.save()
        return user
