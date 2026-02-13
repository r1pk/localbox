<?php

namespace App\Enum;

enum UserRole: string
{
    case Admin = 'ROLE_ADMIN';

    case User = 'ROLE_USER';

    public static function getChoices(): array
    {
        $choices = [];

        foreach (self::cases() as $role) {
            $choices[$role->name] = $role->value;
        }

        return $choices;
    }
}
