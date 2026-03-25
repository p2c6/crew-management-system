<?php

namespace App\Http\Resources\Crew;

use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Rank\RankResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CrewResource extends JsonResource
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
            'rank' => $this->whenLoaded('rank', fn() => new RankResource($this->rank)),
            'documents' => $this->whenLoaded('documents', fn() =>  DocumentResource::collection($this->documents)),
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'birth_date' => $this->birth_date,
            'age' => $this->age,
            'height' => $this->height,
            'weight' => $this->weight,
            'bmi' => $this->bmi,
            'email' => $this->email,
        ];
    }
}
