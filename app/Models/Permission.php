<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Permission extends Model
{
    protected $guarded = [
        'entity_id',
        'permission_name',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
