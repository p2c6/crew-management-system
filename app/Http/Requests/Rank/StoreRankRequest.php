<?php

namespace App\Http\Requests\Rank;

use Illuminate\Foundation\Http\FormRequest;

class StoreRankRequest extends FormRequest
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
            'code' => 'required|unique:ranks,code,except,id',
            'short_name' => 'required|string',
            'alias' => 'required|string',
        ];
    }
}
