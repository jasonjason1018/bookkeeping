<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class LineBotController extends Controller
{
    public function getMessage(request $request)
    {
        try {
            Log::channel('daily')->info($request->query());
        } catch (Exception $e) {
            Log::channel('daily')->error('錯誤');
        }

        return 'hahaha';
    }
}
