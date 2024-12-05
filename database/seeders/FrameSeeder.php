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
            'name' => "frame 1" , 
            'slug' => "frame-1" , 
            'image' => "frame-1" , 
            'status' => 1
        ]);
        Frame::create([
            'name' => "frame 2" , 
            'slug' => "frame-2" , 
            'image' => "frame-2" , 
            'status' => 1
        ]);
        Frame::create([
            'name' => "frame 3" , 
            'slug' => "frame-3" , 
            'image' => "frame-3" , 
            'status' => 1
        ]);
    }
}
