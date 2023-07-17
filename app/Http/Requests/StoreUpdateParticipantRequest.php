<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateParticipantRequest extends FormRequest
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
            'first_driver_id' => 'required|integer|min:0',
            'second_driver_id' => 'required|integer|min:0',
            'vehicle_id' => 'required|integer|min:0',
            'can_compete' => 'boolean',
        ];
    }
}
