<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class AdminMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::user()
            && in_array(Auth::user()->role, [UserRoleEnum::Admin->value, UserRoleEnum::SuperAdmin->value])
        ) {
            return $next($request);
        }

        throw new UnauthorizedException(__('exceptions.This action is unauthorized'));
    }
}
