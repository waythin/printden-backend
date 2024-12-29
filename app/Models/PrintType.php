<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function size(){
        return $this->hasMany(Size::class);
    }
}
