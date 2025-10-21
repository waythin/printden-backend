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
            'name' => "Matte Paper" , 
            'slug' => "matte-paper", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Glossy Paper" , 
            'slug' => "glossy-paper", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Canvas Paper" , 
            'slug' => "canvas-paper", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Fine Art Paper" , 
            'slug' => "fine-art-paper", 
            'status' => 1,
        ]);
        PrintType::create([
            'name' => "Dry Matte Paper", 
            'slug' => "dry-matte-paper", 
            'status' => 1,
        ]);
        
        PrintType::create([
            'name' => "Luster Paper", 
            'slug' => "luster-paper", 
            'status' => 1,
        ]);
    }
}
