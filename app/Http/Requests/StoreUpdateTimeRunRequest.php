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
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'time_mils' => 'integer|min:0',
            'time_secs' => 'integer|min:0',
            'started' => 'boolean',
            'arrived' => 'boolean',
            'penalty' => 'boolean',
            'penalty_updated_by' => 'integer|min:0',
            'penalty_notes' => 'boolean|string|max:255',
            'time_points' => 'integer|min:0',
            'run_points' => 'integer|min:0'
        ];
    }
}
