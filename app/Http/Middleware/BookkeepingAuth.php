<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BookkeepingAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $list = ['user_login', 'login', 'logout'];
    public function handle(Request $request, Closure $next)
    {
        $uri = explode('/', request()->path());
        $path = $uri[0] == ''?request()->path():$uri[0];
        $param = isset($uri[1])?$uri[1]:'';
        
        if(! in_array($path, $this->list)){
            if($path == '/'){
                return $next($request);        
            }
            if(!$request->session()->get($request->cookie('admin_token').'_is_login')){
                return redirect("/login");
            }
        }
        return $next($request);
    }
}
