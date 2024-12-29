<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function getImageAttribute($value)
    {
        if (!$value) {
            return asset('dummy/no_image.png');
        }
        // Ensure the full URL is returned
        return url('/admin/frames/' . ltrim($value, '/'));
    }
}
