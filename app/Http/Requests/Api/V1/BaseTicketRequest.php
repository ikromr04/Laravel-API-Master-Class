<?php

namespace App\Http\Requests\Api\V1;

class BaseTicketRequest extends BaseFormRequest
{
    protected array $mapAttributes = [
        'data.id' => 'id',
        'data.attributes.title' => 'title',
        'data.attributes.description' => 'description',
        'data.attributes.status' => 'status',

        'data.relationships.author.data.id' => 'user_id'
    ];
}
