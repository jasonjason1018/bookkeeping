<?php

namespace App\Console\Commands;

use App\Models\QrcodeSerial;
use Illuminate\Console\Command;

class GenerateQrCodeUri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_qrcode_uri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $characters;

    const KEY_NUMBER = array(3, 7, 1, 5, 9, 4, 8, 2, 6);

    public function __construct()
    {
        parent::__construct();
        $this->characters = range('A', 'Z');
        QrcodeSerial::truncate();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $this->start();
        $this->proccess();
        $this->end();
    }

    private function start(): void
    {
        $this->start = now();
        $this->memoryUsage = memory_get_peak_usage();
    }

    private function proccess()
    {
        set_time_limit(300);
        $url = "https://www.kirin.com.tw/g/scankey?keyno=";
        $uri = "GL";
        //$serialNumber = $this->getSerialNumber(4);
        $startId = 1;
        $uriArray = [];
        $serialQrIsDuplicate = [];
        while (true) {
            if ($startId > 80000) {
                break;
            }

            $formattedNumber = sprintf("%05d", $startId);
            $serialNumber = $this->getSerialNumber(4);
            $serial = $formattedNumber . $serialNumber;
            $serialArray = str_split($serial);
            $checkNumber = $this->getCheckNumber($serialArray);
            $serialNumberUri = $uri . $serial . $checkNumber;
            $startId ++ ;
            if (in_array($serialNumberUri, $serialQrIsDuplicate)) {
                QrcodeSerial::truncate();
                return $this->proccess();
            } else {
                $serialQrIsDuplicate[] = $serialNumberUri;
                $uriArray[] = [
                    'serial' => $serialNumberUri,
                    'url' => $url.$serialNumberUri
                ];
            }
            if (collect($uriArray)->count()%5000 == 0) {
                QrcodeSerial::insert($uriArray);
                $uriArray = [];
            }
        }

        $this->totalRow = QrcodeSerial::count();
    }

    private function getSerialNumber($length)
    {
        shuffle($this->characters);
        $serialNumberArray = array_rand(array_flip($this->characters), $length);
        $serialNumber = implode("", $serialNumberArray);

        return $serialNumber;
    }

    private function getCheckNumber($serialArray)
    {
        $checkSum = 0;
        foreach ($serialArray as $key => $serialNumber) {
            $checkSum += ord($serialNumber) * self::KEY_NUMBER[$key];
        }

        $checkNumberKey = $checkSum%count($this->characters);
        $checkNumber = $this->characters[$checkNumberKey];
        return $checkNumber;
    }

    private function end(): void
    {
        $timeUsage = now()->diffInSeconds($this->start);
        $this->info(sprintf('執行命令：%s', $this->name));
        $this->info(sprintf('序號共：%s筆', $this->totalRow));
        $this->info(sprintf('共耗時：%s秒', $timeUsage));
        $this->info(sprintf('共使用記憶體: %sM', (memory_get_peak_usage() - $this->memoryUsage) / 1024 / 1024));
    }
}
