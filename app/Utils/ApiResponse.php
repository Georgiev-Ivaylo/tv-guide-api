<?php

namespace App\Utils;

class ApiResponse
{
    public static function success($data, $metadata = [], $message = null, $links = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => $metadata,
            'links' => $links,
            'session' => 'awesome',
        ], 200);
    }

    public static function error($code = 400, $message = null, $data = [], $metadata = [], $links = [])
    {
        if ($code >= 600 || !is_numeric($code)) {
            $code = 500;
        }
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => $metadata,
            'links' => $links,
            'session' => 'awesome error',
        ], $code);
    }

    // public static function error($message, $code = 400, $details = [])
    // {
    //     return response()->json([
    //         'status' => 'error',
    //         'error' => [
    //             'code' => $code,
    //             'message' => $message,
    //             'details' => $details
    //         ],
    //         'metadata' => [
    //             'timestamp' => now(),
    //         ]
    //     ], $code);
    // }
}
