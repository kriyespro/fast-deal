"""
seed_data.py — create demo cities, localities, broker user, and properties
Run: python manage.py shell < seed_data.py
"""
import os, django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'core.settings')
django.setup()

from users.models import User, Role
from properties.models import City, Locality, Property, PropertyType, ListingType, PropertyStatus

# Cities
city_data = [
    ('Mumbai', 'Maharashtra'), ('Delhi NCR', 'Delhi'), ('Bangalore', 'Karnataka'),
    ('Hyderabad', 'Telangana'), ('Pune', 'Maharashtra'), ('Surat', 'Gujarat'),
    ('Ahmedabad', 'Gujarat'), ('Chennai', 'Tamil Nadu'),
]
cities = {}
for name, state in city_data:
    c, _ = City.objects.get_or_create(name=name, defaults={'state': state})
    cities[name] = c
print(f'Cities: {City.objects.count()}')

# Localities
locality_data = {
    'Mumbai': ['Bandra West', 'Juhu', 'Powai', 'Andheri West', 'Worli'],
    'Bangalore': ['Koramangala', 'Whitefield', 'Indiranagar', 'HSR Layout', 'Jayanagar'],
    'Surat': ['Vesu', 'Adajan', 'Athwa', 'Piplod', 'Pal'],
    'Pune': ['Koregaon Park', 'Baner', 'Kharadi', 'Viman Nagar', 'Hadapsar'],
    'Hyderabad': ['Banjara Hills', 'Jubilee Hills', 'Gachibowli', 'Hitech City', 'Madhapur'],
}
for city_name, locs in locality_data.items():
    c = cities.get(city_name)
    if c:
        for loc in locs:
            Locality.objects.get_or_create(city=c, name=loc)
print(f'Localities: {Locality.objects.count()}')

# Demo broker user
broker, created = User.objects.get_or_create(
    email='broker@propsurat.in',
    defaults={'full_name': 'Rajesh Patel', 'role': Role.BROKER, 'phone': '+91 98765 43210'}
)
if created:
    broker.set_password('broker1234')
    broker.save()
print(f'Broker: {broker}')

# Demo properties
props = [
    dict(title='3 BHK Luxury Apartment, Koramangala', city='Bangalore', locality='Koramangala',
         property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
         price=8500000, area_sqft=1450, bedrooms=3, bathrooms=3,
         furnishing='semi', rera_id='KA/RERA/2024/001', is_rera_verified=True,
         amenities='Gym,Swimming Pool,Parking,Security,Power Backup',
         is_featured=True, description='Premium 3 BHK in the heart of Koramangala with all modern amenities.'),
    dict(title='2 BHK Flat for Rent, Bandra West', city='Mumbai', locality='Bandra West',
         property_type=PropertyType.APARTMENT, listing_type=ListingType.RENT,
         price=55000, area_sqft=980, bedrooms=2, bathrooms=2,
         furnishing='furnished', is_featured=True,
         amenities='Parking,Security,Lift,Intercom',
         description='Fully furnished 2 BHK in prime Bandra West location.'),
    dict(title='4 BHK Villa, Jubilee Hills', city='Hyderabad', locality='Jubilee Hills',
         property_type=PropertyType.HOUSE, listing_type=ListingType.SALE,
         price=25000000, area_sqft=3200, bedrooms=4, bathrooms=4,
         furnishing='furnished', is_rera_verified=True, is_featured=True,
         amenities='Gym,Pool,Garden,Parking,Power Backup,Security',
         description='Stunning 4 BHK villa in Jubilee Hills with private pool.'),
    dict(title='1 BHK Apartment, Adajan Surat', city='Surat', locality='Adajan',
         property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
         price=3200000, area_sqft=620, bedrooms=1, bathrooms=1,
         furnishing='unfurnished', is_rera_verified=True,
         amenities='Lift,Parking,Security',
         description='Affordable 1 BHK in fast-growing Adajan area of Surat.'),
    dict(title='2 BHK Apartment for Rent, Whitefield', city='Bangalore', locality='Whitefield',
         property_type=PropertyType.APARTMENT, listing_type=ListingType.RENT,
         price=28000, area_sqft=1100, bedrooms=2, bathrooms=2,
         furnishing='semi',
         amenities='Gym,Parking,Security,Club House',
         description='Well-maintained 2 BHK near IT corridor in Whitefield.'),
    dict(title='Plot for Sale, Baner Pune', city='Pune', locality='Baner',
         property_type=PropertyType.PLOT, listing_type=ListingType.SALE,
         price=7500000, area_sqft=2400, bedrooms=None, bathrooms=None,
         amenities='Corner Plot,Road Facing',
         description='Prime residential plot in Baner with clear title and RERA approved layout.'),
    dict(title='3 BHK Premium Flat, Powai Mumbai', city='Mumbai', locality='Powai',
         property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
         price=18500000, area_sqft=1650, bedrooms=3, bathrooms=3,
         furnishing='semi', is_rera_verified=True, is_featured=True,
         amenities='Gym,Pool,Jogging Track,Parking,Security',
         description='Premium 3 BHK in Hiranandani Complex, Powai with lake view.'),
    dict(title='Commercial Office Space, Hitech City', city='Hyderabad', locality='Hitech City',
         property_type=PropertyType.COMMERCIAL_OFFICE, listing_type=ListingType.RENT,
         price=120000, area_sqft=2500, bedrooms=None, bathrooms=4,
         amenities='24x7 Power,Parking,Security,CCTV,Lift',
         description='Grade A office space in Hitech City with modern infrastructure.'),
]

from properties.models import Locality as LocalityModel
for p in props:
    city_name = p.pop('city')
    locality_name = p.pop('locality')
    city_obj = cities.get(city_name)
    locality_obj = LocalityModel.objects.filter(city=city_obj, name=locality_name).first()
    prop, created = Property.objects.get_or_create(
        title=p['title'],
        defaults={
            **p,
            'city': city_obj,
            'locality': locality_obj,
            'broker': broker,
            'status': PropertyStatus.ACTIVE,
        }
    )
    if created:
        print(f'  + {prop.title} — {prop.price_display}')

print(f'\nTotal properties: {Property.objects.count()}')
print('\nTest Credentials:')
print('  Broker: broker@propsurat.in / broker1234')
print('  Admin:  admin@propsurat.in / admin1234')
