<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class LineBotController extends Controller
{
<<<<<<< HEAD
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
=======
    public function getMessage(Request $request)
    {
        // 檢查是否有 `validationToken`，這表示這是來自 Microsoft Graph 的驗證請求
        if ($request->has('validationToken')) {
            // 返回 `validationToken` 作為純文本
            return response($request->input('validationToken'), 200)
                ->header('Content-Type', 'text/plain');
        }

        // 如果不是驗證請求，處理來自 Graph 的通知
        try {
            $get = $request->all();
            Log::channel('daily')->info($get);
        } catch (Exception $e) {
            Log::channel('daily')->error('錯誤: ' . $e->getMessage());
        }

        return response()->noContent(200);
    }

>>>>>>> 652d8001a264464bfd565b2a355154e1504e9ab5
}
