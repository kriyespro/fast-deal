<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $properties = [
            [
                'title' => 'Luxurious Sea-View Apartment in Bandra',
                'slug' => 'luxurious-sea-view-apartment-bandra',
                'description' => "A stunning 3BHK apartment with panoramic sea views in the heart of Bandra West. This fully furnished premium home features a modern chef's kitchen, elegant wooden flooring, smart home automation, and a private balcony overlooking the Arabian Sea.\n\nThe spacious living area is bathed in natural light with floor-to-ceiling glass windows. Building amenities include rooftop infinity pool, fully equipped gym, 24/7 security, and concierge service.\n\nPrime location—walking distance to Carter Road promenade, upscale restaurants, boutiques, and entertainment venues that define Bandra's cosmopolitan lifestyle.",
                'price' => 25000000,
                'listing_type' => 'sale',
                'property_type' => 'Apartment',
                'status' => 'available',
                'address' => '14 Waroda Road, Bandra West',
                'city' => 'Mumbai',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 2100,
                'features' => json_encode(['Sea View', 'Furnished', 'Swimming Pool', 'Gym', 'Smart Home', '24/7 Security', 'Parking', 'Concierge']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Modern Villa with Private Pool in Koregaon Park',
                'slug' => 'modern-villa-private-pool-koregaon-park',
                'description' => "Experience resort-style living in this architect-designed 4BHK villa nestled in the serene lanes of Koregaon Park. The property features a private heated swimming pool, landscaped garden, and a double-height living room with Italian marble flooring.\n\nThe gourmet modular kitchen, Netflix-ready home theater, and private study make this an entertainer's dream. The master suite boasts a luxurious en-suite with a freestanding bathtub and walk-in closet.\n\nLocated minutes from the German Bakery, Osho Commune, fine-dining restaurants, and Pune's vibrant café culture.",
                'price' => 55000000,
                'listing_type' => 'sale',
                'property_type' => 'Villa',
                'status' => 'available',
                'address' => '7B Lane 5, Koregaon Park',
                'city' => 'Pune',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'area_sqft' => 5200,
                'features' => json_encode(['Private Pool', 'Furnished', 'Garden', 'CCTV', 'Gym', 'Home Theater', 'Modular Kitchen', 'Parking']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Contemporary Studio near Cyber City',
                'slug' => 'contemporary-studio-cyber-city-gurugram',
                'description' => "Ideal for young professionals, this smart studio apartment is located in a premium high-rise just minutes from Cyber City, DLF Phase 2, and HUDA City Centre Metro. The fully-furnished unit features a built-in workspace, convertible sofa-bed, and a compact modular kitchen.\n\nBuilding facilities include Co-working space, rooftop café, 24/7 concierge, and express laundry services. High-speed 500 Mbps fibre internet included in the rent.\n\nPerfect for corporate rentals or Airbnb investment with strong yield potential in Gurugram's booming rental market.",
                'price' => 35000,
                'listing_type' => 'rent',
                'property_type' => 'Studio',
                'status' => 'available',
                'address' => 'Sector 43, DLF Phase 2',
                'city' => 'Gurugram',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqft' => 480,
                'features' => json_encode(['Fully Furnished', 'Wifi Included', 'Co-working Space', 'Smart TV', 'Air Conditioning', '24/7 Security', 'Gym', 'Laundry']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Penthouse Suite at Prestige Falcon City',
                'slug' => 'penthouse-prestige-falcon-city-bangalore',
                'description' => "The crown jewel of Prestige Falcon City—an ultra-premium 5BHK sky penthouse on the 42nd floor offering 270-degree panoramic views of Bangalore's skyline and Nandi Hills on clear days.\n\nSpanning across 7,500 sq ft of living space, the penthouse features a private rooftop terrace with a jacuzzi, butler's quarters, a world-class chef's kitchen, a library lounge, and bespoke Italian furniture throughout.\n\nProviding the highest standard of security and privacy. Access to 5 swimming pools, 3 clubhouses, tennis courts, and a dedicated concierge floor. The pinnacle of Bangalore's luxury residential market.",
                'price' => 180000000,
                'listing_type' => 'sale',
                'property_type' => 'Penthouse',
                'status' => 'available',
                'address' => 'Prestige Falcon City, Kanakapura Road',
                'city' => 'Bangalore',
                'bedrooms' => 5,
                'bathrooms' => 6,
                'area_sqft' => 7500,
                'features' => json_encode(['Rooftop Jacuzzi', 'Private Terrace', 'Butler Service', 'Smart Home', 'Gym & Spa', '5 Swimming Pools', 'Tennis Courts', 'Concierge']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => '3BHK Heritage Bungalow in Defence Colony',
                'slug' => '3bhk-heritage-bungalow-defence-colony-delhi',
                'description' => "A rare gem—a beautifully restored independent bungalow in the prestigious Defence Colony of South Delhi. This 3BHK property retains its classic colonial charm with high ceilings, large verandahs, and a private garden while featuring modern amenities throughout.\n\nThe fully renovated interior features designer bathrooms, a state-of-the-art modular kitchen, and premium Kajaria flooring. The extensive garden with a seating area is perfect for al fresco dining and weekend entertaining.\n\nWalking distance to Defence Colony market, Lodi Garden, Khan Market, and the diplomatic enclave. An irreplaceable slice of Delhi at its finest.",
                'price' => 120000,
                'listing_type' => 'rent',
                'property_type' => 'Bungalow',
                'status' => 'available',
                'address' => 'Block A, Defence Colony',
                'city' => 'New Delhi',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'area_sqft' => 3200,
                'features' => json_encode(['Private Garden', 'Verandah', 'Modular Kitchen', 'Air Conditioning', 'CCTV', 'Parking', 'Staff Quarters', 'Power Backup']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Riverside Farmhouse on ECR',
                'slug' => 'riverside-farmhouse-ecr-chennai',
                'description' => "Escape the city in this spectacular 4-acre farmhouse estate along the East Coast Road, just 35 km from Chennai. A perfect weekend retreat or permanent residence, this property features a main bungalow, a guest cottage, a lap pool, and a small private beach access point.\n\nThe main house offers 4 large bedrooms with en-suite bathrooms, an open-plan living and dining area, and a fully equipped outdoor kitchen with a barbecue pit. The landscaped grounds feature mango and coconut groves, a kitchen garden, and a meditation gazebo.\n\nIdeal for holiday home investment with proven Airbnb rental income of ₹60,000+ per weekend.",
                'price' => 78000000,
                'listing_type' => 'sale',
                'property_type' => 'Farmhouse',
                'status' => 'available',
                'address' => 'ECR, Mahabalipuram Road km 35',
                'city' => 'Chennai',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'area_sqft' => 8000,
                'features' => json_encode(['Beach Access', 'Swimming Pool', 'Guest Cottage', 'Mango Grove', 'Outdoor Kitchen', 'Air Conditioning', 'Solar Power', 'Borewell']),
                'main_image' => null,
                'agent_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $db = \Config\Database::connect();

        // Only insert if table is empty to avoid duplicate seed
        $count = $db->table('properties')->countAll();
        if ($count === 0) {
            $db->table('properties')->insertBatch($properties);
            echo "Inserted " . count($properties) . " sample properties.\n";
        } else {
            echo "Properties table already has {$count} records. Skipping seed.\n";
        }
    }
}
