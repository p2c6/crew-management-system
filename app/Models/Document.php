<?php

namespace App\Models;

use Dom\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function crew(): HasMany
    {
        return $this->hasMany(Crew::class);
    }

    public function documentType(): HasOne
    {
        return $this->hasOne(DocumentType::class);
    }
}
