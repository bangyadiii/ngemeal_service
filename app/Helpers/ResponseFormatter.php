<?php

namespace App\Helpers;

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
    public static function error($message = "Service Unavailable", $code = 500, $data = null)
    {
        self::$response['meta']["code"] = $code;
        self::$response['meta']["message"] = $message;
        self::$response['meta']["status"] = "error";
        self::$response["data"] = $data;

        return response()->json(self::$response, $code);
    }
}
