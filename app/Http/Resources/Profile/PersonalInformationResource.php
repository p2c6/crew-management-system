<?php
namespace App\Http\Resources\Profile;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PersonalInformationResource extends JsonResource
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
            'first_name' => $this->firstName(),
            'last_name' => $this->lastName()
        ];
    }

    public function firstName()
    {
        return explode(" ", $this->full_name)[0] ?? null;
    }

    public function lastName()
    {
        return explode(" ", $this->full_name)[1] ?? null;
    }
}