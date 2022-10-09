<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * arr of available languages
 */
const ALLOWED_LANGUAGES = [
    'en',
    'pl',
];

/**
 * @param Throwable $throwable
 * @return void
 */
if (!function_exists('reportError')) {
    function reportError(Throwable $throwable): void
    {
        Log::error(
            $throwable->getMessage() . PHP_EOL
            . 'in line: ' . $throwable->getLine() . PHP_EOL
            . 'in file: ' . $throwable->getFile()
        );
    }
}

/**
 * @param Request $request
 * @return string
 */
if (!function_exists('getRequestLang')) {
    function getRequestLang(Request $request): string
    {
        $lang = 'en';
        $requestLang = strtolower($request->header('Accept-Language'));

        foreach (ALLOWED_LANGUAGES as $allowed) {
            if (str_contains($requestLang, $allowed)) {
                $lang = $allowed;
            }
        }
        return $lang;
    }
}

/**
 * convert timestamps to human readable dates
 * @param Carbon|null $date
 * @return string|null
 */
if (!function_exists('timestampToString')) {
    function timestampToString(?Carbon $date): ?string
    {
        return $date?->toDateTimeString();
    }
}

