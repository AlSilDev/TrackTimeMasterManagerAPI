<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateVehicleRequest extends FormRequest
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
            'model' => 'required|string|max:50',
            'class_id' => 'required|integer|min:0',
            'license_plate' => 'required|string',
            'year' => 'required|integer|digits:4',
            'engine_capacity' => 'required|integer|min:0',
        ];
    }
}
