"""
Seed demo cities, localities, broker, and active properties.

Usage (local):  python manage.py seed_demo
Usage (docker): docker compose exec web python manage.py seed_demo
"""
from django.core.management.base import BaseCommand

from users.models import User, Role
from properties.models import (
    City, Locality, Property, PropertyType, ListingType, PropertyStatus,
)


class Command(BaseCommand):
    help = 'Seed demo cities, localities, broker user, and ACTIVE properties'

    def handle(self, *args, **options):
        city_data = [
            ('Surat', 'Gujarat'),
        ]
        cities = {}
        for name, state in city_data:
            c, _ = City.objects.get_or_create(name=name, defaults={'state': state})
            cities[name] = c
        self.stdout.write(f'Cities: {City.objects.count()} (Surat focus)')

        locality_data = {
            'Surat': [
                'Vesu', 'Adajan', 'Athwa', 'Piplod', 'Pal',
                'City Light', 'Althan', 'Katargam', 'Varachha', 'Dumas',
                'VIP Road', 'Palanpur',
            ],
        }
        for city_name, locs in locality_data.items():
            c = cities.get(city_name)
            if c:
                for loc in locs:
                    Locality.objects.get_or_create(city=c, name=loc)
        self.stdout.write(f'Localities: {Locality.objects.filter(city__name="Surat").count()} (Surat)')

        broker, created = User.objects.get_or_create(
            email='broker@propsurat.in',
            defaults={
                'full_name': 'Rajesh Patel',
                'role': Role.BROKER,
                'phone': '+91 98765 43210',
            },
        )
        if created:
            broker.set_password('broker1234')
            broker.save()
        self.stdout.write(f'Broker: {broker.email}')

        props = [
            dict(title='3 BHK Luxury Apartment, Vesu', city='Surat', locality='Vesu',
                 property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
                 price=8500000, area_sqft=1450, bedrooms=3, bathrooms=3,
                 furnishing='semi', rera_id='GJ/RERA/SURAT/2024/001', is_rera_verified=True,
                 amenities='Gym,Swimming Pool,Parking,Security,Power Backup',
                 is_featured=True, description='Premium 3 BHK in Vesu with clubhouse, pool, and covered parking.'),
            dict(title='2 BHK Flat for Rent, Athwa', city='Surat', locality='Athwa',
                 property_type=PropertyType.APARTMENT, listing_type=ListingType.RENT,
                 price=22000, area_sqft=980, bedrooms=2, bathrooms=2,
                 furnishing='furnished', is_featured=True,
                 amenities='Parking,Security,Lift,Intercom',
                 description='Fully furnished 2 BHK near Athwa gates — ideal for family rental.'),
            dict(title='4 BHK Villa, Piplod', city='Surat', locality='Piplod',
                 property_type=PropertyType.HOUSE, listing_type=ListingType.SALE,
                 price=18500000, area_sqft=3200, bedrooms=4, bathrooms=4,
                 furnishing='furnished', is_rera_verified=True, is_featured=True,
                 amenities='Gym,Pool,Garden,Parking,Power Backup,Security',
                 description='Spacious 4 BHK villa near Piplod mall road with private garden.'),
            dict(title='1 BHK Apartment, Adajan Surat', city='Surat', locality='Adajan',
                 property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
                 price=3200000, area_sqft=620, bedrooms=1, bathrooms=1,
                 furnishing='unfurnished', is_rera_verified=True,
                 amenities='Lift,Parking,Security',
                 description='Affordable 1 BHK in fast-growing Adajan area of Surat.'),
            dict(title='2 BHK Apartment for Rent, Pal', city='Surat', locality='Pal',
                 property_type=PropertyType.APARTMENT, listing_type=ListingType.RENT,
                 price=18000, area_sqft=1100, bedrooms=2, bathrooms=2,
                 furnishing='semi',
                 amenities='Gym,Parking,Security,Club House',
                 description='Quiet 2 BHK rental in Pal with society amenities.'),
            dict(title='Plot for Sale, Althan', city='Surat', locality='Althan',
                 property_type=PropertyType.PLOT, listing_type=ListingType.SALE,
                 price=5500000, area_sqft=2400, bedrooms=None, bathrooms=None,
                 amenities='Corner Plot,Road Facing',
                 description='Prime residential plot in Althan with clear title.'),
            dict(title='3 BHK Premium Flat, City Light', city='Surat', locality='City Light',
                 property_type=PropertyType.APARTMENT, listing_type=ListingType.SALE,
                 price=7200000, area_sqft=1650, bedrooms=3, bathrooms=3,
                 furnishing='semi', is_rera_verified=True, is_featured=True,
                 amenities='Gym,Pool,Jogging Track,Parking,Security',
                 description='Ready-to-move 3 BHK on City Light Road with premium finishes.'),
            dict(title='Commercial Office Space, VIP Road', city='Surat', locality='VIP Road',
                 property_type=PropertyType.COMMERCIAL_OFFICE, listing_type=ListingType.RENT,
                 price=75000, area_sqft=1800, bedrooms=None, bathrooms=3,
                 amenities='24x7 Power,Parking,Security,CCTV,Lift',
                 description='Office space on VIP Road — high footfall commercial corridor.'),
        ]

        created_n = 0
        activated_n = 0
        for p in props:
            city_name = p.pop('city')
            locality_name = p.pop('locality')
            city_obj = cities.get(city_name)
            locality_obj = Locality.objects.filter(city=city_obj, name=locality_name).first()
            defaults = {
                **p,
                'city': city_obj,
                'locality': locality_obj,
                'broker': broker,
                'status': PropertyStatus.ACTIVE,
            }
            prop, created = Property.objects.get_or_create(
                title=p['title'],
                defaults=defaults,
            )
            if created:
                created_n += 1
                self.stdout.write(f'  + {prop.title} → /property/{prop.slug}/')
            else:
                # Ensure existing demo rows are ACTIVE + featured as intended
                dirty = False
                if prop.status != PropertyStatus.ACTIVE:
                    prop.status = PropertyStatus.ACTIVE
                    dirty = True
                    activated_n += 1
                if p.get('is_featured') and not prop.is_featured:
                    prop.is_featured = True
                    dirty = True
                if city_obj and prop.city_id != city_obj.id:
                    prop.city = city_obj
                    dirty = True
                if locality_obj and prop.locality_id != getattr(locality_obj, 'id', None):
                    prop.locality = locality_obj
                    dirty = True
                if not prop.slug:
                    dirty = True
                if dirty:
                    prop.save()
                self.stdout.write(f'  = {prop.title} → /property/{prop.slug}/ (status={prop.status})')

        demo_titles = [
            '3 BHK Luxury Apartment, Vesu',
            '2 BHK Flat for Rent, Athwa',
            '4 BHK Villa, Piplod',
            '1 BHK Apartment, Adajan Surat',
            '2 BHK Apartment for Rent, Pal',
            'Plot for Sale, Althan',
            '3 BHK Premium Flat, City Light',
            'Commercial Office Space, VIP Road',
        ]
        fixed = Property.objects.filter(title__in=demo_titles).exclude(
            status=PropertyStatus.ACTIVE
        ).update(status=PropertyStatus.ACTIVE)
        if fixed:
            self.stdout.write(f'  ! force-activated {fixed} demo rows')

        active = Property.objects.filter(status=PropertyStatus.ACTIVE).count()
        featured = Property.objects.filter(status=PropertyStatus.ACTIVE, is_featured=True).count()
        self.stdout.write(self.style.SUCCESS(
            f'Done. total={Property.objects.count()} active={active} '
            f'featured={featured} created={created_n} activated={activated_n}'
        ))
        if active == 0:
            self.stderr.write(self.style.ERROR('WARNING: still 0 ACTIVE properties — check DB connection'))
            raise SystemExit(1)

        # Attach multiple demo photos (uses fixtures/demo_images/)
        from django.core.management import call_command
        self.stdout.write('\n=== Seeding property images ===')
        call_command('seed_images')
