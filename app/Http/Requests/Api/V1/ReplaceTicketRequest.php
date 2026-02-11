<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TicketStatus;
use Illuminate\Validation\Rule;

class ReplaceTicketRequest extends BaseTicketRequest
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
        $rules = [
            'data.attributes.title' => ['required', 'string'],
            'data.attributes.description' => ['required', 'string'],
            'data.attributes.status' => ['required', 'string', Rule::in(TicketStatus::values())],
            'data.relationships.author.data.id' => ['required', 'exists:users,id']
        ];

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        if ($this->routeIs('authors.tickets.replace')) {
            $this->merge([
                'data.relationships.author.data.id' => $this->route('author')
            ]);
        }
    }
}
