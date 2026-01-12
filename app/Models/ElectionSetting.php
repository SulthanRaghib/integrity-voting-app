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
     */
    public static function isVotingOpen(): bool
    {
        $setting = self::first();

        if (! $setting || ! $setting->start_at || ! $setting->end_at) {
            return false; // Not configured, so closed.
        }

        return Carbon::now()->between($setting->start_at, $setting->end_at);
    }
}
