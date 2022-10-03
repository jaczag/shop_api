<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

trait ApiRequest
{
    /**
     * @param string $url
     * @param array $data
     * @param bool $urlencode
     * @param $auth
     * @param $headers
     * @param bool $get_errors
     * @param bool $get_only_contents
     * @return array|false|mixed|string|null
     * @throws GuzzleException
     */
    protected function sendGet(
        string $url,
        array $data,
        bool $urlencode = false,
        $auth = null,
        $headers = null,
        bool $get_errors = false,
        bool $get_only_contents = false
    ) {
        $paramsUrl = [];

        foreach ($data as $key => $value) {
            $paramsUrl[] = "{$key}=" . ($urlencode ? urlencode($value) : $value);
        }

        if ($paramsUrl) {
            $url .= '?' . implode('&', $paramsUrl);
        }

        try {
            $client = new Client();

            $response = $client->request('GET', $url, [
                'auth' => $auth,
                'headers' => $headers,
            ]);

            if ($get_only_contents) {
                return $response->getBody()->getContents();
            }

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            try {
                if ($get_only_contents) {
                    return $response->getBody()->getContents();
                }

                $body = json_decode($response->getBody()->getContents(), true);
            } catch (Exception $exception) {
                $body = null;
            }

            return $body;
        } catch (Exception $ex) {
            if ($get_errors) {
                return [
                    'is_error' => true,
                    'message' => $ex->getMessage(),
                ];
            }
        }

        return false;
    }
}
