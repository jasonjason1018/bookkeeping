<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\QrcodeSerial;

class QrcodeController extends Controller
{
    public function execGenerateQrcodeCommand()
    {
        Artisan::call('generate_qrcode_uri');
        $output = Artisan::output();
        dd($output);
    }

    public function getQrcodeSerialNumberList($limit = 0)
    {
        $serialList = QrcodeSerial::select('id', 'serial','url')
            ->where('is_download', '=', 0)
            ->take($limit)
            ->get();

        foreach ($serialList as $serial) {
            $updateId[] = $serial->id;
            if (collect($updateId)->count()%10 == 0) {
                QrcodeSerial::whereIn('id', $updateId)->update(['is_download' => 1]);
                $updateId = [];
            }
        }

        return response($serialList);
    }
}
