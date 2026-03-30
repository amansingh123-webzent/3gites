<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Birthday extends Model
{
    protected $fillable = ['user_id', 'birth_month', 'birth_day', 'birth_year'];

    protected $casts = ['birth_year' => 'integer'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this birthday falls today (ignoring year).
     */
    public function isToday(): bool
    {
        return $this->birth_month === (int) now()->format('n')
            && $this->birth_day   === (int) now()->format('j');
    }

    /**
     * Scope: birthdays this month (for dashboard widget).
     */
    public function scopeThisMonth($query)
    {
        return $query->where('birth_month', now()->month);
    }
}
