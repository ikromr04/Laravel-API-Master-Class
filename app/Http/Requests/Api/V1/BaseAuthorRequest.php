<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseAuthorRequest extends FormRequest
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
            'data.attributes.name' => 'name',
            'data.attributes.email' => 'email',
            'data.attributes.password' => 'password'
        ])
            ->filter(fn($to, $from) => $this->filled($from))
            ->mapWithKeys(fn($to, $from) => [$to => $this->input($from)])
            ->toArray();
    }
}
