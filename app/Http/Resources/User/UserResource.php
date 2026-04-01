<?php

namespace App\Http\Resources\User;

use App\Http\Resources\AccessModule\AccessModuleResource;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->whenLoaded('role', fn() => new RoleResource($this->role)),
            'access_modules' => $this->whenLoaded('accessModules', fn() => AccessModuleResource::collection($this->accessModules)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
