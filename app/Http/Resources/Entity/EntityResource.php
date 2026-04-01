<?php

namespace App\Http\Resources\Entity;

use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Rank\RankResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'icon' => $this->icon,
            'is_main' => $this->is_main,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
