<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        return response()->json([
            'result' => true,
            'message' => ['head' => 'hello', 'body' => 'world']
        ], 200);
    }
}
