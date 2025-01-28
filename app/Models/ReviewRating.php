<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        if(!$value){
            return url('dummy/no_image.png');
        }
        return url('/admin/reviews/' . ltrim($value, '/'));
    }
}
