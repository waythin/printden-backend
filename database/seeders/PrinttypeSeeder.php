<?php

namespace Database\Seeders;

use App\Models\PrintType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrinttypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrintType::create([
            'name' => "HD matte sticker paper" , 
            'slug' => "hd-matte-sticker-paper", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "3mm Board Hd matte pasted frame" , 
            'slug' => "3mm-board-hd-matte-pasted-frame", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "5mm Board Hd matte pasted frame" , 
            'slug' => "5mm-board-hd-matte-pasted-frame", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Premium Framed Print with Glass" , 
            'slug' => "premium-framed-print-with-glass", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Premium Framed Print wothout glass", 
            'slug' => "premium-framed-print-without-glass", 
            'status' => 1,
        ]);
    }
}
