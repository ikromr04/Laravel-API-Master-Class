<?php

namespace App\Http\Requests\Api\V1;

class BaseAuthorRequest extends BaseFormRequest
{
    protected array $mapAttributes = [
        'data.attributes.name' => 'name',
        'data.attributes.email' => 'email',
        'data.attributes.password' => 'password'
    ];
}
