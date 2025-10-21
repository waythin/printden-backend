<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => "Print Photo" , 
            'slug' => "print-photo" , 
            'status' => 1
        ]);
        Service::create([
            'name' => "Frame Photo" , 
            'slug' => "frame-photo" , 
            'status' => 1
        ]);
        Service::create([
            'name' => "Collage Photo" , 
            'slug' => "collage-photo" , 
            'status' => 1
        ]);
        Service::create([
            'name' => "Photo Album" , 
            'slug' => "photo-album" , 
            'status' => 1
        ]);
        Service::create([
            'name' => "Print Mug" , 
            'slug' => "print-mug" , 
            'status' => 0
        ]);
        Service::create([
            'name' => "T-shirt Print" , 
            'slug' => "tshirt-print" , 
            'status' => 0
        ]);
        Service::create([
            'name' => "Tote Bag" , 
            'slug' => "tote-bag" , 
            'status' => 0
        ]);
        
    }
}
