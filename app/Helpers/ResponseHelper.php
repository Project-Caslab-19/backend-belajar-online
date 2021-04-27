<?php
    namespace App\Helpers;

    class ResponseHelper{
        public static function responseValidation($errors)
        {
            return response()->json([
                'error' => true,
                'message' => (!is_object($errors)) ? $errors : $errors->all(),
            ], 422);
        }

        public static function responseError($msg, $code)
        {
            return response()->json([
                'error' => true,
                'message' => $msg,
            ], $code);
        }

        public static function responseSuccess($msg)
        {
            return response()->json([
                'error' => false,
                'message' => $msg,
            ], 200);
        }

        public static function responseSuccessWithData($data)
        {
            return response()->json([
                'error' => false,
                'data' => $data
            ], 200);
        }
    }
?>