<?php

namespace App\Http\Requests\Document;

use App\Rules\ContainsDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_type_id' => 'required|exists:document_types,id',
            'code'             => 'required|string|max:255',
            'existing_document_id' => 'nullable',
            'issued_date'      => 'required|date',
            'expiry_date'      => 'required|date|after_or_equal:issued_date',
            'documents'        => [new ContainsDocument],
            'documents.*' => 'required|string',
        ];
    }
}
