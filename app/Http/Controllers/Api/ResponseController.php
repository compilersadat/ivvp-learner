<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function sendResponse($response)
    {
        return response()->json($response, 200);
    }


    public function sendError($error, $code = 404)
    {
    	$response = [
            'message' => $error,
        ];
        return response()->json($response, $code);
    }
}