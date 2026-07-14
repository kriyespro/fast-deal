from django import forms
from .models import Inquiry, Project, City, Locality, Property

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


class PropertyForm(forms.ModelForm):
    """Broker listing form — multi-image upload via request.FILES.getlist('images')."""

    class Meta:
        model = Property
        fields = [
            'title', 'property_type', 'listing_type', 'city', 'locality',
            'address', 'price', 'area_sqft', 'bedrooms', 'bathrooms',
            'furnishing', 'rera_id', 'amenities', 'description',
        ]
        widgets = {
            'title': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'e.g. 3 BHK Flat, Vesu'}),
            'property_type': forms.Select(attrs={'class': INPUT_CLASS}),
            'listing_type': forms.Select(attrs={'class': INPUT_CLASS}),
            'city': forms.Select(attrs={'class': INPUT_CLASS}),
            'locality': forms.Select(attrs={'class': INPUT_CLASS}),
            'address': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'Building, street, landmark'}),
            'price': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '5500000'}),
            'area_sqft': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '1200'}),
            'bedrooms': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '3', 'min': 0}),
            'bathrooms': forms.NumberInput(attrs={'class': INPUT_CLASS, 'placeholder': '2', 'min': 0}),
            'furnishing': forms.Select(attrs={'class': INPUT_CLASS}),
            'rera_id': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'RERA/GJ/...'}),
            'amenities': forms.TextInput(attrs={'class': INPUT_CLASS, 'placeholder': 'Gym, Parking, Pool, Security'}),
            'description': forms.Textarea(attrs={'class': INPUT_CLASS, 'rows': 3, 'placeholder': 'Property ke baare mein...'}),
        }
        labels = {
            'title': 'Property Title',
            'property_type': 'Type',
            'listing_type': 'Sale / Rent',
            'city': 'City',
            'locality': 'Locality',
            'address': 'Address',
            'price': 'Price (₹)',
            'area_sqft': 'Area (sqft)',
            'bedrooms': 'BHK',
            'bathrooms': 'Bathrooms',
            'furnishing': 'Furnishing',
            'rera_id': 'RERA Number',
            'amenities': 'Amenities (comma separated)',
            'description': 'Description',
        }

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fields['locality'].queryset = Locality.objects.filter(is_active=True).select_related('city')
        self.fields['city'].queryset = City.objects.filter(is_active=True)
        self.fields['locality'].required = False
        self.fields['rera_id'].required = False
        self.fields['amenities'].required = False
        self.fields['description'].required = False
        self.fields['bedrooms'].required = False
        self.fields['bathrooms'].required = False
        self.fields['area_sqft'].required = False


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
