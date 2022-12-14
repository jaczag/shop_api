<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class GetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Request
     */
    public function handle(Request $request, Closure $next)
    {
        App::setLocale(getRequestLang($request));
        return $next($request);
    }
}
