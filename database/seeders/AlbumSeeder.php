<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Album::create([
            'name' => "Square 1" , 
            'slug' => "square-1" , 
            'type' => "square" , 
            'image' => "square-1.jpg" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "Square 2" , 
            'slug' => "square-2" , 
            'type' => "square" , 
            'image' => "square-2.jpg" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "Square 3" , 
            'slug' => "square-3" , 
            'type' => "square" , 
            'image' => "square-3.jpg" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "Rectangle 1" , 
            'slug' => "rectangle-1" , 
            'type' => "rectangle" , 
            'image' => "rectangle-1.jpg" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "Rectangle 2" , 
            'slug' => "rectangle-2" , 
            'type' => "rectangle" , 
            'image' => "rectangle-2.jpg" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "Rectangle 3" , 
            'slug' => "rectangle-3" , 
            'type' => "rectangle" , 
            'image' => "rectangle-3.jpg" , 
            'status' => 1
        ]);
    }
}
