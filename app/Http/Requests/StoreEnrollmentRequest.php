<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
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
            'enroll_order' => 'required|integer|min:0',
            'run_order' => 'integer|min:0',
            'first_driver_id' => 'required|integer|min:0',
            'second_driver_id' => 'required|integer|min:0',
            'vehicle_id' => 'required|integer|min:0',
            'enrolled_by_id' => 'required|integer|min:0'
        ];
    }
}
