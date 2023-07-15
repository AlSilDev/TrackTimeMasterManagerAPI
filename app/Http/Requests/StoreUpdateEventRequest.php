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
            'date_end_enrollments' => 'date|after:date_start_enrollments',
            'date_start_event' => 'date|after:date_end_enrollments',
            'date_end_event' => 'date|after:date_start_event',
            'image_file' => 'nullable|file|image|max:5120',
            'course_file' => 'nullable|file|image|max:5120',
            'category_id' => 'required',
            'base_penalty' => 'required|integer',
            'point_calc_reason' => 'required',
        ];
    }
}
