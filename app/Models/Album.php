<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        if(!$value){
            return asset('dummy/no_image.png');
        }
        return url('/admin/albums/' . ltrim($value, '/'));
    }

  

}
