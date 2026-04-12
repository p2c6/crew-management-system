<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\DocumentType\DocumentTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'name' => $this->name,
            'crew_id' => $this->crew_id,
            'document_type' => $this->whenLoaded('documentType', fn() => new DocumentTypeResource($this->documentType)),
            'user_id' => $this->person_in_charge_id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'code' => $this->code,
            'issued_date' => $this->getDateFormats($this->issued_date),
            'expiry_date' =>  $this->getDateFormats($this->expiry_date),
        ];
    }

    public function getDateFormats(object $date): array
    {
        return [
            'original' => $date,
            'for_human' => $date->toFormattedDateString(),
        ];
    }
}
