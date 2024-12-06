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
            'name' => "album 1" , 
            'slug' => "album-1" , 
            'image' => "album-1" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "album 2" , 
            'slug' => "album-2" , 
            'image' => "album-2" , 
            'status' => 1
        ]);
        Album::create([
            'name' => "album 3" , 
            'slug' => "album-3" , 
            'image' => "album-3" , 
            'status' => 1
        ]);
    }
}