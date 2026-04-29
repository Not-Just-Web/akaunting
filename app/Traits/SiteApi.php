<?php

namespace App\Traits;

use Akaunting\Version\Version;
use App\Utilities\Info;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

trait SiteApi
{
    public static $base_uri = 'https://api.akaunting.com/';

    protected static function siteApiRequest($method, $path, $extra_data = [])
    {
        // Disabled: App is self-contained and does not connect to cloud
        return false;
    }

    public static function getResponse($method, $path, $data = [], $status_code = 200)
    {
        // Disabled: App is self-contained and does not connect to cloud
        return false;
    }

    public static function getResponseBody($method, $path, $data = [], $status_code = 200)
    {
        // Disabled: App is self-contained and does not connect to cloud
        return [];
    }

    public static function getResponseData($method, $path, $data = [], $status_code = 200)
    {
        if (! $body = static::getResponseBody($method, $path, $data, $status_code)) {
            return [];
        }

        if (! is_object($body)) {
            return [];
        }

        return $body->data;
    }
}
