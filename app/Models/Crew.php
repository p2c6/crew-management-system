<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'birth_date',
    ];

    public function crewDocuments(): HasMany
    {
        return $this->hasMany(CrewDocument::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }
}
