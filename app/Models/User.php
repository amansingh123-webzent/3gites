<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, CausesActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'member_status',
        'account_locked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'account_locked'    => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function tribute(): HasOne
    {
        return $this->hasOne(Tribute::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function pollVotes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function birthday(): HasOne
    {
        return $this->hasOne(Birthday::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->member_status === 'active';
    }

    public function isSearching(): bool
    {
        return $this->member_status === 'searching';
    }

    public function isDeceased(): bool
    {
        return $this->member_status === 'deceased';
    }

    public function isLocked(): bool
    {
        return (bool) $this->account_locked;
    }

    /**
     * Override default auth: block locked accounts from logging in.
     */
    public function canLogIn(): bool
    {
        return ! $this->account_locked && $this->member_status !== 'deceased';
    }
}
