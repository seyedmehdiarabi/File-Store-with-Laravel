<?php


namespace Modules\users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\users\Repository\AdditionalInfoRepositoryInterface;

class AdditionalInfoController extends Controller
{
    public function profile($id){
        return CView('users::additionalInfo'.$this->view.'.profile',compact('id'));
    }

    public function confirmMobile(){
        if(\Session::has('mobile_number')){
            $layout=$this->view=='mobile.' ? 'mobile' : 'desktop';
            $margin=$this->view=='mobile.' ? '10' : '25';
            return view('users::auth.confirm-mobile',['layout'=>$layout,'margin'=>$margin]);
        }
        else{
            return redirect('/');
        }
    }

    public function changeMobileNumber(Request $request, AdditionalInfoRepositoryInterface $additionalInfoRepository)
    {
        $mobile=$request->get('mobile');
        $active_code=$request->get('active_code');
        $user_id=$request->user()->id;
        $additionalInfo=$additionalInfoRepository->find([
            'user_id'=>$user_id,
            'mobile_phone'=>$mobile
        ]);
        if($additionalInfo){
            $user=$request->user();
            if($user->active_code==$active_code){
                $user->active_code=null;
                $user->mobile=$mobile;
                $user->update();
                return  redirect('user/profile/additional-info')->with('status','ثبت اطلاعات با موفقیت انجام شد');
            }
            else{
                return  redirect()->back()
                    ->with('mobile_number',$mobile)
                    ->with(['validate_error'=>'کد فعال سازی وارد شده اشتباه می باشد']);
            }
        }
        else{
            return redirect('/');
        }
    }
}
