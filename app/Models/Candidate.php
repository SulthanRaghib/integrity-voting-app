<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vision',
        'mission',
        'photo_path',
        'order_number',
        'votes_count',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
