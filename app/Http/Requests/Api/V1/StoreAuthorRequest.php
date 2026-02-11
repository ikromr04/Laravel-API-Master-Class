<?php

namespace App\Http\Requests\Api\V1;

use App\Models\User;
use Illuminate\Http\Response;

class StoreAuthorRequest extends BaseAuthorRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Handle failed authorization for the FormRequest.
     */
    protected function failedAuthorization(): void
    {
        $this->error(
            'You are not authorized to create a new author.',
            Response::HTTP_FORBIDDEN
        )->send();

        exit;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.name' => ['required', 'string'],
            'data.attributes.email' => ['required', 'email', 'unique:users,email'],
            'data.attributes.password' => ['required', 'string', 'min:6', 'confirmed'],
            'data.attributes.password_confirmation' => ['required', 'string'],
        ];
    }
}
