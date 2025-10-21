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
            ['name' => "4R", 'slug' => "4R", 'dimensions' => "4 inch * 6 inch", 'prices' => [90, 149, 200, 320, 250, 400]],
            ['name' => "8L", 'slug' => "8L", 'dimensions' => "8 inch * 12 inch", 'prices' => [130, 249, 300, 550, 350, 600]],
            ['name' => "10R", 'slug' => "10R", 'dimensions' => "10 inch * 12 inch", 'prices' => [230, 349, 400, 750, 500, 850]],
            ['name' => "12R", 'slug' => "12R", 'dimensions' => "12 inch * 16 inch", 'prices' => [430, 649, 800, 1400, 1000, 1600]],
            ['name' => "12L", 'slug' => "12L", 'dimensions' => "12 inch * 18 inch", 'prices' => [450, 689, 850, 1450, 1500, 1700]],
            ['name' => "20R", 'slug' => "20R", 'dimensions' => "20 inch * 24 inch", 'prices' => [530, 749, 900, 1500, 1600, 1800]],
            ['name' => "20L", 'slug' => "20L", 'dimensions' => "20 inch * 30 inch", 'prices' => [630, 849, 900, 1600, 1700, 1900]],
            ['name' => "Extra Large", 'slug' => "extra-large", 'dimensions' => "24 inch * 36 inch", 'prices' => [830, 1049, 1200, 1800, 1900, 2100]]
        ];

        // Loop through print types (1 to 6)
        foreach (range(1, 6) as $printTypeId) {
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
