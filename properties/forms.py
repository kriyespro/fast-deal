from django import forms
from .models import Inquiry, Project, City, Locality

INPUT_CLASS = 'w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-orange-500 text-sm'


class InquiryForm(forms.ModelForm):
    class Meta:
        model = Inquiry
        fields = ('name', 'phone', 'email', 'message')
        widgets = {
            'name': forms.TextInput(attrs={'placeholder': 'Aapka naam', 'class': INPUT_CLASS}),
            'phone': forms.TextInput(attrs={'placeholder': '+91 98765 43210', 'class': INPUT_CLASS}),
            'email': forms.EmailInput(attrs={'placeholder': 'aapka@email.com (optional)', 'class': INPUT_CLASS}),
            'message': forms.Textarea(attrs={
                'placeholder': 'Koi sawaal ya requirement likhein...',
                'class': INPUT_CLASS, 'rows': 3
            }),
        }
        labels = {
            'name': 'Naam', 'phone': 'Phone', 'email': 'Email', 'message': 'Message',
        }


class ProjectForm(forms.ModelForm):
    class Meta:
        model = Project
        fields = [
            'name', 'project_type', 'status', 'city', 'locality',
            'address', 'price_min', 'price_max', 'total_units',
            'completion_pct', 'rera_id', 'description', 'expected_possession', 'image',
        ]
        widgets = {
            'name': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'e.g. Surat Heights Phase 2'}),
            'project_type': forms.Select(attrs={'class': INPUT_CLASS}),
            'status': forms.Select(attrs={'class': INPUT_CLASS}),
            'city': forms.Select(attrs={'class': INPUT_CLASS}),
            'locality': forms.Select(attrs={'class': INPUT_CLASS}),
            'address': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'Locality, City - Pincode'}),
            'price_min': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '5500000'}),
            'price_max': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '12000000 (optional)'}),
            'total_units': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '120'}),
            'completion_pct': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '0–100', 'min': 0, 'max': 100}),
            'rera_id': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'RERA/GJ/R/2024/XXXXXX'}),
            'description': forms.Textarea(attrs={'class': INPUT_CLASS, 'rows': 3, 'placeholder': 'Project features, amenities, possession date...'}),
            'expected_possession': forms.DateInput(attrs={'class': INPUT_CLASS, 'type': 'date'}),
        }
        labels = {
            'name': 'Project Name', 'project_type': 'Type', 'status': 'Status',
            'city': 'City', 'locality': 'Locality', 'address': 'Address',
            'price_min': 'Starting Price (₹)', 'price_max': 'Max Price (₹, optional)',
            'total_units': 'Total Units', 'completion_pct': 'Completion %',
            'rera_id': 'RERA Number', 'description': 'About Project',
            'expected_possession': 'Expected Possession', 'image': 'Project Image',
        }


class BuilderProfileForm(forms.Form):
    full_name = forms.CharField(max_length=150, widget=forms.TextInput(attrs={'class': INPUT_CLASS}))
    phone = forms.CharField(max_length=15, required=False, widget=forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': '+91 98765 43210'}))
    company_name = forms.CharField(max_length=150, required=False, widget=forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'Your company name'}))
    rera_number = forms.CharField(max_length=50, required=False, widget=forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'RERA/GJ/...'}))
    website = forms.URLField(required=False, widget=forms.URLInput(attrs={'class': INPUT_CLASS, 'placeholder': 'https://yourcompany.com'}))
    bio = forms.CharField(required=False, widget=forms.Textarea(attrs={'class': INPUT_CLASS, 'rows': 3, 'placeholder': 'About your company...'}))


class PropertyFilterForm(forms.Form):
    q = forms.CharField(required=False, widget=forms.TextInput(attrs={'placeholder': 'City, locality ya keyword...'}))
    city = forms.CharField(required=False)
    listing_type = forms.ChoiceField(
        required=False,
        choices=[('', 'Buy / Rent'), ('sale', 'Buy'), ('rent', 'Rent'), ('pg', 'PG')]
    )
    property_type = forms.ChoiceField(
        required=False,
        choices=[
            ('', 'All Types'),
            ('apartment', 'Apartment'),
            ('house', 'House/Villa'),
            ('plot', 'Plot'),
            ('commercial_office', 'Office'),
            ('commercial_shop', 'Shop'),
        ]
    )
    bedrooms = forms.ChoiceField(
        required=False,
        choices=[('', 'Any BHK'), ('1', '1 BHK'), ('2', '2 BHK'), ('3', '3 BHK'), ('4', '4+ BHK')]
    )
    max_price = forms.DecimalField(required=False, min_value=0)
