<?php

namespace App\Http\Resources\AccessModule;

use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\Rank\RankResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessModuleResource extends JsonResource
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
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'entity' => $this->whenLoaded('entity', fn() => new EntityResource($this->entity)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
