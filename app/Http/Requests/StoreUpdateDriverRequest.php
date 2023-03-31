<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateDriverRequest extends FormRequest
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
            'email' => 'required|email',
            'license_num' => 'required|integer|min:0',
            'license_expiry' => 'required|date',
            'phone_num' => 'required||regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:9',
            'affiliate_num' => 'required|integer|min:0',
        ];
    }
}
