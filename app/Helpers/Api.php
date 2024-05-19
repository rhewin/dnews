<?php

if (!function_exists('my_custom_helper')) {
    /**
     * Example helper function.
     *
     * @param  string  $text
     * @return string
     */
    function respOk($message, $data = null, $meta = null)
    {
        return [
            'status' => 'success',
            'data' => $data,
            'meta' => $meta,
            'message' => $message
        ];
    }

    function respError($message, $code)
    {
        return [
            'status' => 'error',
            'error' => $message,
            'code' => $code
        ];
    }
}
