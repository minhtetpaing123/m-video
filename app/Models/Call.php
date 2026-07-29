<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Call extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'caller_id',
        'receiver_id',
        'channel_name',
        'type',
        'status',
        'end_reason',
        'duration',
        'started_at',
        'ended_at',
        'is_encrypted',
        'quality_rating',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_encrypted' => 'boolean',
        'duration' => 'integer',
        'quality_rating' => 'integer',
    ];

    /**
     * ဖုန်းခေါ်ဆိုသူ (User Relationship)
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    /**
     * ဖုန်းလက်ခံသူ (User Relationship)
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Call ကြာချိန် (Duration) ကို Automatic တွက်ပေးမည့် Helper
     */
    public function markAsEnded(string $reason = 'completed'): void
    {
        $endedAt = now();
        $duration = $this->started_at ? $this->started_at->diffInSeconds($endedAt) : 0;

        $this->update([
            'status' => 'ended',
            'end_reason' => $reason,
            'ended_at' => $endedAt,
            'duration' => $duration,
        ]);
    }
}
