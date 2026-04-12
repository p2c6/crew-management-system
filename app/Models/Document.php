<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_id',
        'document_type_id',
        'file_name',
        'file_path',
        'code',
        'issued_date',
        'expiry_date',
        'user_id',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function crew(): HasMany
    {
        return $this->hasMany(Crew::class);
    }

    public function documentType(): HasOne
    {
        return $this->hasOne(DocumentType::class);
    }
}
