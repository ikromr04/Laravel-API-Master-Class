<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    /**
     * Map JSON:API payload to domain attributes.
     */
    public function mappedAttributes(): array
    {
        return $this->mapAttributes();
    }

    /**
     * Extract scalar attributes from JSON:API payload.
     */
    protected function mapAttributes(): array
    {
        return collect([
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.relationships.author.data.id' => 'user_id'
        ])
            ->filter(fn($to, $from) => $this->filled($from))
            ->mapWithKeys(fn($to, $from) => [$to => $this->input($from)])
            ->toArray();
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'data.attributes.status' => 'The data.attributes.status value is invalid. Please use A, C, H, or X.'
        ];
    }
}
