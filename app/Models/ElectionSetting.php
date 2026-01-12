<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ElectionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Check if the voting period is strictly open.
     * Now respects Asia/Jakarta timezone (config/app.php).
     */
    public static function isVotingOpen(): bool
    {
        $setting = self::first();

        if (! $setting || ! $setting->start_at || ! $setting->end_at) {
            return false; // Not configured, so closed.
        }

        $now = now(); // Uses config('app.timezone') = Asia/Jakarta
        return $now->between($setting->start_at, $setting->end_at);
    }

    /**
     * Get the current setting instance.
     */
    public static function current(): ?self
    {
        return self::first();
    }
}
