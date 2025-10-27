<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function size ()
    {
        return $this->belongsTo(Size::class);
    }
}
