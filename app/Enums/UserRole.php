<?php

namespace App\Enums;

enum UserRole: string
{
    case SystemAdministrator = 'system_administrator';
    case Staff = 'staff';

    public function id(): int
    {
        return match ($this) {
            self::SystemAdministrator => 1,
            self::Staff => 2,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::SystemAdministrator => 'System Administrator',
            self::Staff => 'Staff',
        };
    }
}