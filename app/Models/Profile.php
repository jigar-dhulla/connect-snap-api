<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'phone',
        'company',
        'job_title',
        'bio',
        'profile_photo',
        'social_url',
        'qr_code_hash',
        'registered_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Profile $profile) {
            if (empty($profile->qr_code_hash)) {
                $profile->qr_code_hash = Str::random(32);
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function connectionsAsScanner(): HasMany
    {
        return $this->hasMany(Connection::class, 'scanner_profile_id');
    }

    public function connectionsAsScanned(): HasMany
    {
        return $this->hasMany(Connection::class, 'scanned_profile_id');
    }

    public function getQrCodeUrlAttribute(): string
    {
        return "connectsnap://u/{$this->qr_code_hash}";
    }
}
