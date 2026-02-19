<?php

namespace App\Enums;

enum UserRole
{
    case SystemAdministrator = 'system_administrator';
    case Staff = 'staff';
}