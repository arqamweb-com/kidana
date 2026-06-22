<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class EgyptDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Destination::query()->firstOrCreate(
            ['slug' => 'egypt'],
            [
                'name' => 'Egypt',
                'image_url' => 'kidana-home-assets/egypt-pyramids.jpg',
                'is_active' => true,
                'sort_order' => 0,
            ],
        );
    }
}
