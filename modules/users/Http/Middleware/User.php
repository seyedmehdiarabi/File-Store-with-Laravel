<?php


namespace Modules\users\Http\Middleware;

use Closure;
use Auth;
class User
{
    public function handle($request, Closure $next){
        if(request()->method()=='GET'){
            run_action('load_backend_data',[]);
        }
        return  $this->panelResponse($request,$next);
    }

    protected function panelResponse($request,$next){
        $role_id=Auth::user()->role_id;
        if(Auth::user()->role=='admin'){
            return $next($request);
        }
        else if($role_id>0){
            if($request->route()->getName()=="error403"){
                return $next($request);
            }
            else if($request->route()->getName()=="admin"){
                return redirect('/panel');
            }
            else if($request->route()->getName()=="author_panel"){
                return $next($request);
            }
            else{
                $AccessList=\Modules\users\Models\User::AccessList();
                $checkUserAccess=checkUserAccess($request->route()->getName(),$AccessList);
                if($checkUserAccess){
                    return $next($request);
                }
                else{
                    return redirect('/403');
                }
            }
        }
        else{
            return redirect('/');
        }
    }
}
