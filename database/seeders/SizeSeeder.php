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
        
        Size::create([
            'name' => "6 inch * 4 inch - S" , 
            'status' => 1
        ]);
        Size::create([
            'name' => "7.5 inch * 5 inch - M" , 
            'status' => 1
        ]);
        Size::create([
            'name' => "9 inch * 6 inch - L" , 
            'status' => 1
        ]);
        Size::create([
            'name' => "12 inch * 8 inch - XL" , 
            'status' => 1
        ]);
        Size::create([
            'name' => "18 inch * 12 inch - XXL" , 
            'status' => 1
        ]); 
    }
}
