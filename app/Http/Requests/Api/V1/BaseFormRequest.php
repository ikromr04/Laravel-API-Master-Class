<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BaseFormRequest extends FormRequest
{
    protected array $mapAttributes = [];

    /**
     * Map JSON:API payload to domain attributes.
     */
    public function mappedAttributes(): array
    {
        return collect($this->mapAttributes)
            ->filter(fn($to, $from) => $this->filled($from))
            ->mapWithKeys(fn($to, $from) => [$to => $this->input($from)])
            ->toArray();
    }

    /**
     * Configure the validator instance.
     * Adds a check to prohibit any extra fields not defined in rules().
     */
    public function withValidator(Validator  $validator): void
    {

        $validator->after(function ($validator) {
            $allowedKeys = array_keys($this->rules());
            $inputKeys = $this->flattenKeys($this->all());

            $normalizedInputKeys = array_map(function ($key) {
                return preg_replace('/\.\d+(\.|$)/', '.*$1', $key);
            }, $inputKeys);

            $invalidKeys = array_values(array_diff($normalizedInputKeys, $allowedKeys));

            foreach ($invalidKeys as $key) {
                $validator->errors()->add($key, "The {$key} field is prohibited.");
            }
        });
    }

    /**
     * Flatten a multi-dimensional array into dot notation keys.
     * Empty arrays/objects are included as keys.
     *
     * @return string[]
     */
    private function flattenKeys(array $array, string $prefix = ''): array
    {
        $keys = [];

        foreach ($array as $key => $value) {
            $fullKey = $prefix === '' ? $key : "$prefix.$key";

            if (is_array($value)) {
                if (empty($value)) {
                    $keys[] = $fullKey;
                } else {
                    $keys = array_merge($keys, $this->flattenKeys($value, $fullKey));
                }
            } else {
                $keys[] = $fullKey;
            }
        }

        return $keys;
    }
}
