<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAdminVerificationRequest extends FormRequest
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
            'event_id' => 'required|integer|min:0',
            'enrollment_order' => 'required|integer|min:0',
            'enrollment_id' => 'required|integer|min:0',
            'verified' => 'required|boolean',
            'notes' => 'string|max:255',
            'verified_by' => 'required|integer|min:0'
        ];
    }
}
