<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Define size data
        $sizes = [
            ['name' => "Small", 'slug' => "small", 'dimensions' => "8.5 inch * 4 inch", 'prices' => [90, 149, 200, 320, 250]],
            ['name' => "Medium", 'slug' => "medium", 'dimensions' => "12 inch * 18 inch", 'prices' => [130, 249, 300, 550, 350]],
            ['name' => "Large", 'slug' => "large", 'dimensions' => "16 inch * 24 inch", 'prices' => [230, 349, 400, 750, 500]],
            ['name' => "Extra Large", 'slug' => "extra-large", 'dimensions' => "24 inch * 36 inch", 'prices' => [430, 649, 800, 1400, 1000]],
        ];

        // Loop through print types (1 to 5)
        foreach (range(1, 5) as $printTypeId) {
            foreach ($sizes as $index => $size) {
                Size::create([
                    'name' => $size['name'],
                    // 'slug' => $size['slug'],
                    'print_type_id' => $printTypeId,
                    'status' => 1,
                    'price' => $size['prices'][$printTypeId - 1],
                    'dimention' => str_replace(['inch *', ' inch'], ['"', '"'], str_replace('*', 'x', $size['dimensions'])),
                ]);
            }
        }

        
    }
}
