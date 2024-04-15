<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use \DateTime;

class BookkeepingController extends Controller
{

    protected function inseeert(){
        $leg = 7;
        $data = [
            '601'=>'調撥轉出',
            '699'=>'加:匯費',
        ];
        foreach($data as $k => $v){
            $ins = [
                'ledger_id' => $leg,
                'name' => $v,
                'code' => $k
            ];
            $this->subject::insert($ins);
            echo $k;
            echo $v;
        }
    }

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
        $data = $this->adminUsers::where('username', $username)
        ->where('password', $password)
        ->first();
        $msg = '帳號或密碼錯誤';
        $code = 500;
        if($data){
            $msg = '登入成功';
            $code = 200;
            $token = $this->generateToken();
            $user_data = $data->only(['username', 'company_id']);
            $this->request->session()->put($token.'_is_login', true);
            $this->request->session()->put($token.'_user_info', $user_data);
            Cookie::queue('company_id', $user_data['company_id'], 60);
            Cookie::queue('admin_token', $token, 60);
        }
        return response()->json(['msg' => $msg], $code);
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

    protected function getCompanyId(){
        return $this->request->cookie('company_id');
    }

    protected function getAccountData(){
        extract($this->post);
        $data = $this->accountDetails
        ->join('ledger_entries', 'account_details.account_type', '=', 'ledger_entries.id')
        ->select('account_details.*', 'ledger_entries.item as account_type_name')
        ->where('account_details.id', $id)
        ->first();
        return $data;
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

    protected function categoryType(){
        return $this->view();
    }

    protected function getCategoryType(){
        $company_id = $this->getCompanyId();
        return $this->categoryType::where('company_id', $company_id)->get();
    }

    protected function saveCategoryType(){
        unset($this->post['title']);
        $this->post['updated_at'] = $this->getTime();
        $company_id = $this->getCompanyId();
        $this->post['company_id'] = $company_id;
        if(isset($this->post['id'])){
            // die(print_r($this->post));
            $this->categoryType::find($this->post['id'])->update($this->post);
            return ;
        }
        $this->post['created_at'] = $this->getTime();
        $this->categoryType::insert($this->post);
    }

    protected function categoryTypeDelete(){
        $this->categoryType::find($this->post['id'])->delete();
    }

    protected function ledgerEntry(){
        return $this->view();
    }

    protected function getLedgerEntryList(){
        $company_id = $this->getCompanyId();
        return $this->ledgerEntry::where('company_id', $company_id)->get();
    }

    protected function saveLedgerEntry(){
        unset($this->post['title']);
        $this->post['update_at'] = $this->getTime();
        $company_id = $this->getCompanyId();
        $this->post['company_id'] = $company_id;
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
		return date("Y-m-d");
    }

    protected function accountList(){
        return $this->view();
    }

    protected function getAccountList(){
        return $this->accountDetails::all();
    }

    protected function reportIncome(){
        return $this->view();
    }

    protected function getAccountListIncome(){
        extract($this->post);
        return $this->accountDetails::where('type', '收入')->whereYear('invoice_date', $year)->get();
    }

    protected function reportRevenue(){
        return $this->view();
    }

    protected function getReportRevenue(){
        extract($this->post);
        return $this->accountDetails::whereYear('invoice_date', $year)->get();
    }

    protected function accountDelete(){
        extract($this->post);
        $this->accountDetails::find($id)->delete();
    }

    protected function accountDetail($param){
        return $this->view($param);
    }

    protected function subject($param){
        return $this->view($param);
    }

    protected function getSubject(){
        extract($this->post);
        return $this->subject::where('ledger_id', $id)->get();
    }

    protected function saveSubject(){
        unset($this->post['title']);
        if(isset($this->post['id'])){
            $this->subject::find($this->post['id'])->update($this->post);
            return ;
        }
        $this->subject::insert($this->post);
    }

    protected function subjectDelete(){
        $this->subject::find($this->post['id'])->delete();
    }
}
