<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\V1\BaseFormRequest;

class LoginRequest extends BaseFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.type' => ['required', 'in:tokens'],
            'data.attributes.email' => ['required', 'email'],
            'data.attributes.password' => ['required', 'string']
        ];
    }
}
