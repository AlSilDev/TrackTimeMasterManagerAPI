<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTechnicalVerificationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'enrollment_id' => 'required|integer|min:0',
            'verified' => 'boolean',
            'notes' => 'nullable|string|max:255',
            'verified_by' => 'nullable|integer|min:0'
        ];
    }
}
