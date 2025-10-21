<?php

namespace Database\Seeders;

use App\Models\Frame;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Frame::create([
            'name' => "Classic Wood" , 
            'slug' => "classic-wood" , 
            'image' => "classic-wood.png" , 
            'status' => 1
        ]);
        Frame::create([
            'name' => "Modern Black" , 
            'slug' => "modern-black" , 
            'image' => "modern-black.png" , 
            'status' => 1
        ]);
        Frame::create([
            'name' => "Elegant White" , 
            'slug' => "elegant-white" , 
            'image' => "elegant-white.png" , 
            'status' => 1
        ]);
    }
}
