<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case User = "user";
    case Admin = "admin";
    case SuperAdmin = "super_admin";
}
