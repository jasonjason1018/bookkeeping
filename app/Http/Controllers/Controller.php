<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AdminUser;
use App\Models\AccountDetail;
use App\Models\LedgerEntry;
use App\Models\ShareDetail;
use App\Models\CategoryType;
use App\Models\Subject;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $post;
    protected $get;
    protected $request;
    protected $path;
    protected $adminUsers;
    protected $accountDetails;
    protected $ledgerEntry;
    protected $shareDetail;
    protected $categoryType;
    protected $company_id;
    protected $subject;
    
    public function __construct(request $request){
        $uri = explode('/', request()->path());
        $this->path = $uri[0] == ''?request()->path():$uri[0];
        $this->post = $request->input();
        $this->get = $request->query();
        // $this->company_id = $request->session()->get($request->cookie('admin_token').'_user_info')['company_id']??'';
        $this->request = $request;
        $this->adminUsers = new AdminUser();
        $this->accountDetails = new AccountDetail();
        $this->ledgerEntry = new LedgerEntry();
        $this->shareDetail = new ShareDetail();
        $this->categoryType = new CategoryType();
        $this->subject = new Subject();
    }

    protected function view($param = false){
        if($param){
            return view("bookkeeping.$this->path", ['param' => $param]);
        }
        return view("bookkeeping.$this->path");
    }

    protected function generateToken($length = 10){
        return hash('sha256', Str::random($length));
    }
}
