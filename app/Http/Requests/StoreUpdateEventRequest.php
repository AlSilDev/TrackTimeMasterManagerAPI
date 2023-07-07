<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateEventRequest extends FormRequest
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
            'name' => 'required|string|max:75',
            'date_start_enrollments' => 'date',
            'date_end_enrollments' => 'date',
            'date_start_event' => 'date',
            'date_end_event' => 'date',
            'image_file' => 'nullable|file|image',
            'course_file' => 'nullable|file',
            'category_id' => 'required',
            'base_penalty' => 'required|integer',
            'point_calc_reason' => 'required',
        ];
    }
}
