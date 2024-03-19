<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use \DateTime; 

class BookkeepingController extends Controller
{
    protected function redirect(){
        return redirect('/login');
    }

    protected function login(){
        if(null !== $this->request->cookie('admin_token') && $this->request->session()->get($this->request->cookie('admin_token').'_is_login')){
            return redirect("/accountForm");
        }
        return $this->view();
    }

    protected function user_login(){
        extract($this->post);
        $data = $this->adminUsers::where('username', $username)->where('password', $password)->first();
        $msg = '帳號或密碼錯誤';
        $code = "500";
        if($data){
            $msg = '登入成功';
            $code = "200";
            $token = $this->generateToken();
            $this->request->session()->put($token.'_is_login', true);
            
            Cookie::queue('admin_token', $token, 60);
            Cookie::queue('username', $data['username'], 60);
        }
        return response()->json(['msg' => $msg, 'code' => $code]);
    }

    protected function index(){
        return redirect("/accountForm");
        // return $this->view();
    }

    protected function logout(){
        $this->request->session()->forget($this->request->cookie('admin_token').'_is_login');
        Cookie::forget('admin_token');
        return redirect('/login');
    }

    protected function accountForm($param = false){
        if($param){
            return $this->view($param);
        }
        return $this->view();
    }

    protected function getAccountData(){
        extract($this->post);
        $data = $this->accountDetails::find($id);
        if(count(explode('\\', $data['account_type'])) > 1){
            $data['account_type'] = json_decode($data['account_type'], 1);
        }
        return $data;
        // return $this->accountDetails::find($id);
    }

    protected function saveForm(){
        extract($this->post);
        if(isset($this->post['img'])){
            unset($this->post['img']);
            $this->post['img'] = $this->post['imgList'];
        }else{
            $this->post['img'] = '[]';
        }
        if(isset($this->post['imgList'])){
            unset($this->post['imgList']);
        }
        if(isset($id)){
            $this->accountDetails::find($id)->update($this->post);
        }else{
            $id = $this->accountDetails::create($this->post);
        }
        if($share){
            $shareData = [
                'id' => $id,
                'start_share_date' => $start_share_date,
                'end_share_date' => $end_share_date,
                'price' => $price
            ];
            $this->saveShareDetail($shareData);
        }
    }

    private function saveShareDetail($shareData = []){
        $price = $shareData['price'];
        $start = new DateTime($shareData['start_share_date']);
        $end = new DateTime($shareData['end_share_date']);
        $account_id = $shareData['id'];
        $interval = date_diff($start, $end);
        $months = ($interval->y *12) + $interval->m;
        $monthPrice = floor($price/($months+1));
        $hasData = $this->shareDetail::where('account_id', $account_id)->first();
        if($hasData){
            $this->shareDetail::where('account_id', $account_id)->delete();
        }
        $total = 0;
        while(true){
            $thisMonthPrice = $monthPrice;
            if($start->format('Y') == $end->format('Y') && $start->format('m') == $end->format('m')){
                $thisMonthPrice = $price - $total;
            }
            $db_data = [
                'account_id' => $account_id,
                'amount' => $thisMonthPrice,
                'date' => $start->format('Y-m')
            ];
            $this->shareDetail::create($db_data);
            $total +=  $thisMonthPrice;
            // 下個月
            $year = $start->format('Y');
            $month = $start->format('m')+1;
            if($month > 12){
                $month = 1;
                $year += 1;
            }
            $newDate = $year.'-'.$month.'-01';
            $start = new DateTime($newDate);
            if($year == $end->format('Y') && $month > $end->format('m')){
                break;
            }
        }
    }

    protected function uploadImg(Request $request){
        $file = $request->file('file');
        $fileName = date('YmdHis') . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $fileName);
        return $fileName;
    }

    protected function ledgerEntry(){
        return $this->view();
    }

    protected function getLedgerEntryList(){
        return $this->ledgerEntry::all();
    }

    protected function saveLedgerEntry(){
        unset($this->post['title']);
        $this->post['update_at'] = $this->getTime();
        if(isset($this->post['id'])){
            // die(print_r($this->post));
            $this->ledgerEntry::find($this->post['id'])->update($this->post);
            return ;
        }
        $this->post['create_at'] = $this->getTime();
        $this->ledgerEntry::insert($this->post);
    }

    protected function ledgerEntryDelete(){
        $this->ledgerEntry::find($this->post['id'])->delete();
    }

    private function getTime(){
        date_default_timezone_set("Asia/Taipei");
		return date("Y-m-d H:i:s");
    }

    protected function accountList(){
        return $this->view();
    }

    protected function getAccountList(){
        return $this->accountDetails::all();
    }

    protected function accountDelete(){
        extract($this->post);
        $this->accountDetails::find($id)->delete();
    }

    protected function accountDetail($param){
        return $this->view($param);
    }
}
