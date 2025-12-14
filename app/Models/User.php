<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_admin',
        'is_verified',
        'subscription_type',
        'subscription_expires_at',
        'avatar',
        'watchlist',
        'watch_history',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_verified' => 'boolean',
            'subscription_expires_at' => 'datetime',
            'watchlist' => 'array',
            'watch_history' => 'array',
            'preferences' => 'array',
        ];
    }

    /**
     * Check if user has active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription_expires_at) {
            return false;
        }
        
        return $this->subscription_expires_at->isFuture();
    }
}
