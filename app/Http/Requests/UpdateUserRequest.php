<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //'email' => 'email:rfc,dns|unique:users,email',
            'email' => 'email:rfc,dns',
            'name' => 'string',
            'photo_file' => 'nullable|file|image'
        ];
    }
}
