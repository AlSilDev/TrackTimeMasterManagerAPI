<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverHistoryRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'email',
            'country' => 'string|max:5',
            'license_num' => 'integer|min:0',
            'license_expiry' => 'date',
            'phone_num' => 'string|min:9|max:20',
            'affiliate_num' => 'integer|min:0',
        ];
    }
}
