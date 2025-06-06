<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $specialties = [
            [
                'name' => 'Wedding',
                'description' => 'Wedding photography and videography services',
                'icon' => 'fas fa-heart',
                'sort_order' => 1,
            ],
            [
                'name' => 'Portrait',
                'description' => 'Professional portrait photography',
                'icon' => 'fas fa-user',
                'sort_order' => 2,
            ],
            [
                'name' => 'Landscape',
                'description' => 'Beautiful landscape and nature photography',
                'icon' => 'fas fa-mountain',
                'sort_order' => 3,
            ],
            [
                'name' => 'Event',
                'description' => 'Corporate and social event photography',
                'icon' => 'fas fa-calendar-alt',
                'sort_order' => 4,
            ],
            [
                'name' => 'Product',
                'description' => 'Commercial product photography',
                'icon' => 'fas fa-box',
                'sort_order' => 5,
            ],
            [
                'name' => 'Street Photography',
                'description' => 'Urban and street photography',
                'icon' => 'fas fa-road',
                'sort_order' => 6,
            ],
            [
                'name' => 'Architecture',
                'description' => 'Architectural and building photography',
                'icon' => 'fas fa-building',
                'sort_order' => 7,
            ],
            [
                'name' => 'Nature',
                'description' => 'Wildlife and nature photography',
                'icon' => 'fas fa-leaf',
                'sort_order' => 8,
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }
    }
}