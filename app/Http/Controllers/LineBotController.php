<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class LineBotController extends Controller
{
    public function getMessage(request $request)
    {
        $get = $request->all();
        try {
            Log::channel('daily')->info($get);
        } catch (Exception $e) {
            Log::channel('daily')->error('錯誤');
        }

        return response()->json(['status' => 'success'], 200);
    }
}
