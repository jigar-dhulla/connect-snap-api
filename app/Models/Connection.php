<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends Model
{
    /** @use HasFactory<\Database\Factories\ConnectionFactory> */
    use HasFactory;

    protected $fillable = [
        'scanner_profile_id',
        'scanned_profile_id',
        'notes',
        'met_at',
    ];

    protected function casts(): array
    {
        return [
            'met_at' => 'datetime',
        ];
    }

    public function scannerProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'scanner_profile_id');
    }

    public function scannedProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'scanned_profile_id');
    }
}
