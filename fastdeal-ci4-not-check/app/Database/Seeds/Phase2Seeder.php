<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Phase2Seeder extends Seeder
{
    public function run()
    {
        // 1. Seed Global Settings
        $settings = [
            ['setting_key' => 'site_name', 'value' => 'FastDeal Properties'],
            ['setting_key' => 'site_phone', 'value' => '+91 738 301 8982'],
            ['setting_key' => 'site_email', 'value' => 'info@fastdeal.in'],
            ['setting_key' => 'site_whatsapp', 'value' => '917383018982'],
            ['setting_key' => 'site_address', 'value' => 'Gaurav Path Road, Pal, Surat, Gujarat 395009'],
            ['setting_key' => 'meta_description', 'value' => 'Premium properties and real estate in India. Find your dream home in Surat, Mumbai, Ahmedabad and more by FastDeal Properties.'],
        ];
        
        foreach ($settings as $setting) {
            $this->db->table('settings')->ignore(true)->insert($setting);
        }

        // 2. Seed Neighborhoods (Indian Cities)
        $neighborhoods = [
            [
                'name' => 'Surat',
                'city' => 'Surat',
                'description' => 'The Diamond City with rapidly growing commercial and residential spaces.',
                'image_path' => 'https://images.unsplash.com/photo-1596443686812-2f45229eebc3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Ahmedabad',
                'city' => 'Ahmedabad',
                'description' => 'A heritage city blending fast-paced modern infrastructure with timeless appeal.',
                'image_path' => 'https://images.unsplash.com/photo-1584992236310-6edddc08acff?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Mumbai',
                'city' => 'Mumbai',
                'description' => 'The financial capital offering premium sea-facing apartments and bustling suburbs.',
                'image_path' => 'https://images.unsplash.com/photo-1522008629172-0c1dbfbcedd0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Vadodara',
                'city' => 'Vadodara',
                'description' => 'The cultural capital known for excellent educational hubs and peaceful residential zones.',
                'image_path' => 'https://images.unsplash.com/photo-1595180425712-402bf0627581?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($neighborhoods as $hood) {
            $this->db->table('neighborhoods')->ignore(true)->insert($hood);
        }

        // 3. Seed Agents
        $agents = [
            [
                'user_id' => 1, // Assume admin user or similar
                'name' => 'Michael Roberts',
                'email' => 'michael@fastdeal.in',
                'phone' => '+91 98765 43210',
                'whatsapp' => '919876543210',
                'photo' => 'https://ui-avatars.com/api/?name=Michael+Roberts&background=0D8ABC&color=fff',
                'bio' => 'Senior Real Estate Consultant specializing in luxury apartments.',
                'experience_years' => 12,
                'specialization' => 'Luxury Apartments',
                'languages' => 'English, Hindi, Gujarati',
                'rating' => 4.9,
            ],
            [
                'user_id' => null,
                'name' => 'Sarah Jenkins',
                'email' => 'sarah@fastdeal.in',
                'phone' => '+91 91234 56789',
                'whatsapp' => '919123456789',
                'photo' => 'https://ui-avatars.com/api/?name=Sarah+Jenkins&background=E53E3E&color=fff',
                'bio' => 'Commercial property expert with a focus on startup offices.',
                'experience_years' => 8,
                'specialization' => 'Commercial',
                'languages' => 'English, Marathi',
                'rating' => 4.7,
            ]
        ];

        foreach ($agents as $agent) {
            $this->db->table('agents')->ignore(true)->insert($agent);
        }
    }
}
