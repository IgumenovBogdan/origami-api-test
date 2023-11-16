<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEntityRequest extends FormRequest
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
    public function rules()
    {
        return [
            'entity_data_name' => 'required|string',
            'entityFormData' => 'required|array',
            'entityFormData.*.group_data_name' => 'required|string',
            'entityFormData.*.data' => 'required|array',
        ];
    }
}
