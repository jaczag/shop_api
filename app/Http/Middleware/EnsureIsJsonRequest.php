<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class EnsureIsJsonRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->expectsJson()) {
            throw new PreconditionFailedHttpException(
                __('exceptions.Missing header option', ['headerOption' => 'Accept: application/json'])
            );
        }
        return $next($request);
    }
}
