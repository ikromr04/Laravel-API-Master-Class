<?php

namespace App\Permissions;

use App\Models\User;

final class Abilities {
    public const CREATE_USER = 'user:create';
    public const REPLACE_USER = 'user:replace';
    public const UPDATE_USER = 'user:update';
    public const DELETE_USER = 'user:delete';

    public const CREATE_TICKET = 'ticket:create';
    public const CREATE_OWN_TICKET = 'ticket:own:create';
    public const REPLACE_TICKET = 'ticket:replace';
    public const REPLACE_OWN_TICKET = 'ticket:own:replace';
    public const UPDATE_TICKET = 'ticket:update';
    public const UPDATE_OWN_TICKET = 'ticket:own:update';
    public const DELETE_TICKET = 'ticket:delete';
    public const DELETE_OWN_TICKET = 'ticket:own:delete';


    public static function getAbilities(User $user) {
        // Don't assign '*'
        if ($user->is_admin) {
            return [
                self::CREATE_USER,
                self::REPLACE_USER,
                self::UPDATE_USER,
                self::DELETE_USER,
                self::CREATE_TICKET,
                self::REPLACE_TICKET,
                self::UPDATE_TICKET,
                self::DELETE_TICKET
            ];
        } else {
            return [
                self::CREATE_OWN_TICKET,
                self::REPLACE_OWN_TICKET,
                self::UPDATE_OWN_TICKET,
                self::DELETE_OWN_TICKET
            ];
        }
    }


}
