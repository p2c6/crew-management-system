<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'email',
        'weight',
        'height',
    ];

    /**
     * Get the crew's bmi.
     */
    protected function bmi(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->weight && $this->height) 
                ? round($this->weight / (($this->height / 100) ** 2), 1) 
                : null
        );
    }

    /**
     * Get the crew's age.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->birth_date)->age,
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }
}
