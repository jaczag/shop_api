<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * arr of available languages
 */
const ALLOWED_LANGUAGES = [
    'en',
    'pl',
];

if (!function_exists('reportError')) {

    /**
     * @param Throwable $throwable
     * @return void
     */
    function reportError(Throwable $throwable): void
    {
        Log::error(
            $throwable->getMessage() . PHP_EOL
            . 'in line: ' . $throwable->getLine() . PHP_EOL
            . 'in file: ' . $throwable->getFile()
        );
    }
}


if (!function_exists('getRequestLang')) {

    /**
     * @param Request $request
     * @return string
     */
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

