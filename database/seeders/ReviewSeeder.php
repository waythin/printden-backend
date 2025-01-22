<?php

namespace Database\Seeders;

use App\Models\ReviewRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReviewRating::create([
            'name' => "Print Photo" , 
            'email' => "ggwp@gmail.com" , 
            'rate' => 4.5 , 
            'comment' =>  "ggwp bro", 
            'image' =>  null, 
            'status' => 1
        ]);
    }
}
