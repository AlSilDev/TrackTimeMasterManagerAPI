<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateStageRequest extends FormRequest
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
            'name' => 'required|string|max:75',
            'date_start' => 'required|date',
            'num_runs' => 'integer',
            'time_until_next_stage_mins' => 'integer',
        ];
    }
}
