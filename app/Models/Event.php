<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'event_date', 'location', 'is_published'];

    protected $casts = ['event_date' => 'datetime', 'is_published' => 'boolean'];

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Rsvp::class)->where('status', 'attending');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())
                     ->where('is_published', true)
                     ->orderBy('event_date');
    }
}
