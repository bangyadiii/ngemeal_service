<?php

namespace App\Helpers;

use Exception;
use Throwable;

class ResponseFormatter
{
    protected static $response  = [
        //
        "meta" => [
            "code" => 200,
            "status" => "success",
            "message" => "OK"
        ],
        "data" => null
    ];


    public static function success($message = "OK", $code = 200, $data = null)
    {
        self::$response['meta']["code"] = $code;
        self::$response['meta']["message"] = $message;
        self::$response["data"] = $data;

        return response()->json(self::$response, $code);
    }
    public static function error($message = "Internal server error.", $code = 500, $data = null)
    {
        self::$response['meta']["code"] = $code;
        self::$response['meta']["message"] = $message;
        self::$response['meta']["status"] = "error";
        self::$response["data"] = $data;

        return response()->json(self::$response, $code);
    }

    public static function errorStatus($code = 500, Throwable $e)
    {
        if ($code == 400) {
            return self::error("BAD REQUEST", 422, $e->getMessage());
        }
        if ($code == 401) {
            return self::error("Unauthorized", 401, $e->getMessage());
        }
        if ($code == 402) {
            return self::error("Payment Required", 402, $e->getMessage());
        }
        if ($code == 403) {
            return self::error("FORBIDDEN", 403, $e->getMessage());
        }
        if ($code == 404) {
            return self::error("NOT FOUND", 404, $e->getMessage());
        }
        if ($code == 422) {
            return self::error("UNPROCESSABLE ENTITIES", 422, $e->getMessage());
        }
        if ($code == 429) {
            return self::error("Too Many Request", 429, $e->getMessage());
        }
        return self::error();
    }
}
