<?php

namespace App\Http\Requests\Crew;

use Illuminate\Foundation\Http\FormRequest;

class StoreCrewRequest extends FormRequest
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
            'rank_id' => 'required',
            'first_name' => 'required',
            'middle_name' =>  'required',
            'last_name' =>  'required',
            'address' => 'required',
            'birth_date' => 'required|date|before:|before:now',
            'email' => 'required|unique:crews,email,except,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ];
    }
}
