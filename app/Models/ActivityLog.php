<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity.
     */
    public static function logActivity(
        ?int $userId,
        string $type,
        string $description,
        array $metadata = []
    ) {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
