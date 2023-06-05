<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePressFileRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:1',
            'press_file' => 'required|file'
        ];
    }
}
