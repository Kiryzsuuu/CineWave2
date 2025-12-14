<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'otps';

    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Check if OTP is valid.
     */
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Mark OTP as used.
     */
    public function markAsUsed()
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Generate a new OTP.
     */
    public static function generate(string $email, string $type = 'verification'): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        self::create([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);

        return $otp;
    }
}
