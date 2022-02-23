<?php


namespace Modules\users\Repository;


use Modules\users\Models\AdditionalInfo;

class EloquentAdditionalInfoRepository implements AdditionalInfoRepositoryInterface
{

    public function first($request)
    {
        $user_id=$request->user()->id;

        return AdditionalInfo::where(['user_id'=>$user_id])
            ->with(['province','city'])->first();
    }

    public function addUserInfo($user, $request)
    {
        $LegalFields=['company_name','company_economic_number','company_national_identity_number','company_registration_number','company_phone'];
        $newsletter=$request->has('newsletter') ? 'yes' : 'no';
        $activeCode=null;
        $additionalInfo=AdditionalInfo::where('user_id',$user->id)->first();
        if($additionalInfo){
            $data=$request->all();
            $data['newsletter']=$newsletter;
            if($data['Legal']=="false"){
                foreach ($LegalFields as $field){
                    $data[$field]=null;
                }
            }
            $additionalInfo->update($data);
        }
        else{
            $additionalInfo=new AdditionalInfo($request->all());
            $additionalInfo->user_id=$user->id;
            $additionalInfo->newsletter=$newsletter;
            $additionalInfo->save();
        }

        $user->name=$request->get('first_name').' '.$request->get('last_name');
        if($user->mobile !=$additionalInfo->mobile_phone){
            $active_code=rand(99999,1000000);
            $user->active_code=$active_code;
            send_auth_sms($user,$active_code);
            $user->update();
            return redirect('/user/profile/confirm-mobile')
                ->with('mobile_number',$additionalInfo->mobile_phone);
        }
        else{
            $user->update();
            return  redirect()->back()->with('status','ثبت اطلاعات با موفقیت انجام شد');
        }
    }

    public function find($where)
    {
        return AdditionalInfo::where($where)->first();
    }

    public function update_mobile($request)
    {
        $user=$request->user();
        $mobile=$request->get('mobile');
        $active_code=rand(99999,1000000);
        $user->active_code=$active_code;
        send_auth_sms($user,$active_code,$mobile);
        $user->update();

        AdditionalInfo::updateOrCreate(
            ['user_id'=>$user->id],
            ['mobile_phone'=>$mobile]
        );
    }
}
