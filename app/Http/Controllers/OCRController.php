<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;

use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\ImageContext;

class OCRController extends Controller
{
    protected $passportInfo;
    protected function ocrImageProcess(request $request)
    {
        $imagePath = $request->file('idFrontImage')->getPathname();
        $this->extractText($imagePath);
        $imagePath = $request->file('idBackImage')->getPathname();
        $this->extractText($imagePath);
        dd($this->passportInfo);
    }
    private function extractText($imagePath)
    {
        $client = new ImageAnnotatorClient();

        try {
            $response = $client->annotateImage(
                fopen($imagePath, 'r'),
                [Type::TEXT_DETECTION]
            );

            $textAnnotations = $response->getTextAnnotations();
            if ($textAnnotations) {
                $fullText = $textAnnotations[0]->getDescription();
                $pattern = '/[^\x{4E00}-\x{9FFF}\x{3400}-\x{4DBF}\x{20000}-\x{2A6DF}\x{2A700}-\x{2B73F}\x{2B740}-\x{2B81F}\x{2B820}-\x{2CEAF}\x{2F800}-\x{2FA1F}a-zA-Z0-9\n]/u';
                $fullText = preg_replace($pattern, '', $fullText);
                $lines = explode("\n", $fullText);
                $this->analyzeText($lines, $fullText);
            } else {
                throw new \Exception('未辨識到圖片上有文字');
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function analyzeText($lines, $fullText)
    {
        foreach ($lines as $key => $line) {
            if (preg_match('/^[A-Z][0-9]{9}$/', $line)) {
                $this->passportInfo['passportID'] = $line;
            }

            if (count(explode('姓名', $line)) > 1) {
                // $nameArray = explode(' ', explode('姓名', $line)[1]);
                // $name = '';
                // foreach ($nameArray as $k => $v) {
                //     if ($v != '') {
                //         $name .= $v;
                //     }
                // }
                if (count(explode('姓名', $fullText)) > 1) {
                    $name = explode('姓名', $fullText)[1];
                }
                $name = explode('姓名', $fullText)[1];
                $name = explode('出生', $name)[0];
                $names = preg_split('/(?<!^)(?!$)/u', $name);
                $str = '';
                $str_ary = [];
                foreach ($names as $k => $name) {
                    if ($name != '') {
                        if (isset($names[$k-2]) && $names[$k-2] == $name && $names[$k-1] == "\n") {
                            continue;
                        }
                        
                        $str .= $name;
                    }
                }
                $name = $str;
                $pattern = '/[\x{4E00}-\x{9FFF}\x{3400}-\x{4DBF}]/u';
                $name = preg_replace($pattern, '', $name);//str_replace(["\n", " ", '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'], "", $name);
                $this->passportInfo['name'] = $name;
            }
            if (count(explode('民國', $line)) > 1 && count(explode('發證日期', $line)) <= 1) {
                $birthday = $line;
                if (count(explode(' ', $birthday)) > 1) {
                    $birthday = explode(' ', $birthday)[1];
                }
                $this->passportInfo['birthday'] = $birthday;
            }
            if (count(explode('性別', $line)) > 1) {
                $gender = explode('性別', $line)[1];
                if (count(explode(' ', $gender)) > 1) {
                    $gender = explode(' ', $gender)[1];
                }
                $this->passportInfo['gender'] = $gender;
            }
            if (count(explode('發證日期', $line)) > 1) {
                $dateOfIssue = explode('發證日期', $line)[1];
                if (count(explode(' ', $dateOfIssue)) > 1) {
                    $dateOfIssue = explode(' ', $dateOfIssue)[1];
                }
                $this->passportInfo['dateOfIssue'] = $dateOfIssue;
            }
            if ($line == '住址' || count(explode('住址', $line)) > 1) {
                if ($line == '住址') {
                    $this->passportInfo['address'] = $lines[$key+1].$lines[$key+2];
                    continue;
                }
                $this->passportInfo['address'] = explode('住址', $line)[1].$lines[$key+1];
            }
        }
    }
}
