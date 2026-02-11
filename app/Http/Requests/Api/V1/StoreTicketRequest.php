<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Permissions\Abilities;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Ticket::class);
    }

    /**
     * Handle failed authorization for the FormRequest.
     */
    protected function failedAuthorization(): void
    {
        $this->error(
            'You are not authorized to create a new ticket.',
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
        $rules = [
            'data.relationships.author.data.id' => ['required', 'exists:users,id'],

            'data.attributes.title' => ['required', 'string'],
            'data.attributes.description' => ['required', 'string'],
            'data.attributes.status' => ['required', 'string', Rule::in(TicketStatus::values())],
        ];

        $user = $this->user();

        if ($user->tokenCan(Abilities::CREATE_OWN_TICKET)) {
            $rules['data.relationships.author.data.id'][] = Rule::in([$user->id]);
            $rules['data.attributes.status'] = ['prohibited'];
        }

        return $rules;
    }
}
