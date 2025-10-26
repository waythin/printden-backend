<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function category()
    {
        return $this->belongsTo(EventCategory::class,'event_category_id');
    }
}
