<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTimeRunRequest extends FormRequest
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
            'run_id' => 'required|integer|min:0',
            'participant_id' => 'required|integer|min:0',
            'arrival_date' => 'date|nullable',
            'departure_date' => 'date|nullable',
            'start_date' => 'date',
            'end_date' => 'date|nullable',
            'time_mils' => 'integer|min:0|nullable',
            'time_secs' => 'integer|min:0|nullable',
            'started' => 'boolean',
            'arrived' => 'boolean',
            'penalty' => 'integer|min:0|nullable',
            'penalty_updated_by' => 'integer|min:0|nullable',
            'penalty_notes' => 'boolean|string|max:255|nullable',
            'time_points' => 'decimal:0,10|min:0|nullable',
            'run_points' => 'decimal:0,10|min:0|nullable'
        ];
    }
}
