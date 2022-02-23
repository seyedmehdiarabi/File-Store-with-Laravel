<?php

function has_access_author_admin($property,$accessValue=null){

    $result=false;
    $accessList=getAccess();
    if($accessList){
        try{
            $accessList=json_decode($accessList);
            if(is_object($accessList))
            {
                if(property_exists($accessList,$property))
                {
                    if(is_array($accessList->$property))
                    {
                        if($accessValue==null){
                            $result=true;
                        }
                        else{
                            foreach($accessList->$property as $key=>$value)
                            {
                                if($value==$accessValue)
                                {
                                    $result=true;
                                }
                            }
                        }
                    }
                }
            }
        }
        catch (\Exception $e){

        }
    }
    return $result;
}

function getAllAccess(){
    $role_id=Auth::user()->role_id;
    $repository=app(\Modules\users\Repository\UserRoleRepositoryInterface::class);
    return $repository->allAccess($role_id);
}

function checkUserAccess($route,$AccessList){
    $access=getAccess();
    $result=false;
    if($access){
        $access=json_decode($access);
        if(is_object($access)){
            foreach($access as $key=>$value)
            {
                if(array_key_exists($key,$AccessList)){
                    $userAccess=$AccessList[$key]['access'];
                    foreach($value as $key2=>$value2){
                        if(array_key_exists($value2,$userAccess)){
                            if(array_key_exists('routes',$userAccess[$value2]))
                            {
                                foreach($userAccess[$value2]['routes'] as $accessRoute){
                                    if($accessRoute==$route){
                                        $result=true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $result;
}

function getAccess(){
    $result=null;
    $modulesMainClass=Config::get('app.modulesMainClass');
    if(array_key_exists('users',$modulesMainClass)) {
        if(method_exists($modulesMainClass['users'],'getUserAccess')){
            $result = $modulesMainClass['users']->getUserAccess();
        }
        return $result;
    }
    return  $result;
}

function personal_user_detail($detail){
    $array=[];
    $array[]=['title'=>' عنوان تشریفاتی :', 'attr'=>'ceremonial_title', 'value'=>getUserPersonalData($detail,'ceremonial_title')];
    $array[]=['title'=>' نام پدر :', 'attr'=>'father_name', 'value'=>getUserPersonalData($detail,'father_name')];
    $array[]=['title'=>'شماره شناسنامه : ','value'=>replace_number(getUserPersonalData($detail,'id_number')) ,'attr'=>'id_number'];
    $array[]=['title'=>'کد ملی : ','value'=>replace_number(getUserPersonalData($detail,'national_identity_number')) ,'attr'=>'national_identity_number'];
    $array[]=['title'=>' تاریخ تولد','value'=>replace_number(getUserPersonalData($detail,'date_of_birth')),'attr'=>'date_of_birth'];
    $array[]=['title'=>' شماره تلفن ثابت :','value'=>replace_number(getUserPersonalData($detail,'phone')) ,'attr'=>'phone'];
    $array[]=['title'=>'شماره کارت بانکی : ','value'=>replace_number(getUserPersonalData($detail,'bank_card_number')) ,'attr'=>'bank_card'];
    $array[]=['title'=>'معرف :','value'=>getUserPersonalData($detail,'reagent') ,'attr'=>'reagent'];
    $array[]=['title'=>'نحوه آشنایی :','value'=>getUserPersonalData($detail,'introduction') ,'attr'=>'introduction'];
    $array[]=['title'=>'ایمیل :','value'=>getUserPersonalData($detail,'email') ,'attr'=>'email'];
    $array[]=['title'=>'توضیحات :','value'=>getUserPersonalData($detail,'description') ,'attr'=>'description'];
    $array=CompleteData('personal_user_detail',$array);
    return $array;
}

function getUserPersonalData($additionalInfo,$att1,$attr2=null)
{
    $result='-';
    if($additionalInfo && !empty($additionalInfo->$att1))
    {
        $result=$additionalInfo->$att1;
        if($attr2){
            $result.=' '. $additionalInfo->$attr2;
        }
    }
    return $result;
}

function send_auth_sms($user,$active_code,$mobile=null){

    $repository=app(\Modules\setting\Repository\SettingRepositoryInterface::class);
    $setting=$repository->getValues([
        'users-channel',
        'users-api-key',
        'users-line_number',
        'users-active_template'
    ]);
    $message='';
    if($setting['users-active_template']=='' && !empty($setting['users-line_number'])){
        $message=config('shop-info.shop_name')."\n";
        $message.='کد تایید';
        $message.=' : '.$code;
    }
    else{
        $message=$active_code;
    }

    $user->notify(new \Modules\setting\Notifications\SendSms(
        [
            'channel'=>$setting['users-channel'],
            'line_number'=>$setting['users-line_number'],
            'api-key'=>$setting['users-api-key'],
            'template'=>$setting['users-active_template'],
            'mobile_number'=>$mobile!==null ? $mobile : $user->mobile,
            'message'=>$message
        ]
    ));
}

function getAccessMenu($sideBarMenu,$access){
    foreach($sideBarMenu as $key=>$value){
        if(checkParentMenuAccess($access,$value['access'])){
            if(array_key_exists('child',$value)){
                foreach($value['child'] as $key2=>$value2){
                    if (!checkAddChildMuenAccess($access,$value2)){
                        unset($sideBarMenu[$key][$key2]);
                    }
                }
            }
        }
        else{
            unset($sideBarMenu[$key]);
        }
    }
    return $sideBarMenu;
}

function validateBankCard($value){
    if(strlen($value)==16)
    {
        $sum=0;
        for($i=0;$i<strlen($value);$i++)
        {
            $n=intval($value[$i]);
            if(checkEven($i))
            {
                $n=$n*2;
                if($n>9){
                    $n=$n-9;
                }
            }
            $sum=$sum+$n;
        }

        if($sum%2==0){
            return  true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function validateNationalIdentityNumber($value){
    if(is_numeric($value)){
        $sum=0;
        for ($i=0;$i<9;$i++)
        {
            $n=intval(substr($value,$i,1));
            $sum+=(10-$i)*$n;
        }
        $ret=$sum%11;
        $parity=intval(substr($value,9,1));
        if(($ret<2 && $ret==$parity) || ($ret>2 && $ret==(11-$parity))){
            return  true;
        }
        else{
            return  false;
        }
    }
    else{
        return  false;
    }
}

function validateData($data){
    $result=false;
    $e=explode('/',$data);
    if(sizeof($e)==3){
        $y=$e[0];
        $m=$e[1];
        $d=$e[2];
        settype($y,'integer');
        settype($m,'integer');
        settype($d,'integer');
        if(strlen($y)==4 && (strlen($m)==1 || strlen($m)==2)  && (strlen($d)==1 || strlen($d)==2)){
            $result=true;
        }
    }
    return $result;
}

function getUserNmae($id){
    $repository = app(\Modules\users\Repository\UsersRepositoryInterface::class);
    $user = $repository->find($id);
    return $user->name;
}
function get_user_detail($farmer)
{
    $array = [];

    if ($farmer->name) {
        $array[] = [
            'label' => 'نام و نام خانوادگی :',
            'value' => $farmer->name
        ];
    }
    if ($farmer->father_name) {
        $array[] = [
            'label' => 'نام پدر :',
            'value' => $farmer->father_name
        ];
    }
    if ($farmer->date_of_birth) {
        $array[] = [
            'label' => 'تاریخ تولد :',
            'value' => replace_number($farmer->date_of_birth)
        ];
    }
    if ($farmer->birth_certificate_number) {
        $array[] = [
            'label' => 'شماره شناسنامه :',
            'value' => replace_number($farmer->birth_certificate_number)
        ];
    }
    if ($farmer->national_identity_number) {
        $array[] = [
            'label' => 'کد ملی :',
            'value' => replace_number($farmer->national_identity_number)
        ];
    }
    if ($farmer->phone) {
        $array[] = [
            'label' => 'تلفن :',
            'value' => replace_number($farmer->phone)
        ];
    }
    if ($farmer->mobile) {
        $array[] = [
            'label' => 'شماره موبایل :',
            'value' => replace_number($farmer->mobile)
        ];
    }
    if ($farmer->familiarity_method) {
        $array[] = [
            'label' => 'نحوه آشنایی :',
            'value' => $farmer->familiarity_method
        ];
    }
    if ($farmer->reagent) {
        $array[] = [
            'label' => 'معرف :',
            'value' => $farmer->reagent
        ];
    }
    if ($farmer->number_of_lands) {
        $array[] = [
            'label' => 'تعداد زمین :',
            'value' => replace_number($farmer->number_of_lands)
        ];
    }
    if ($farmer->performance_rate) {
        $performance=\Modules\farmer\Models\Farmer::PerformanceRate();
        $array[] = [
            'label' => 'میزان عملکرد :',
            'value' => replace_number($performance[$farmer->performance_rate])
        ];
    }
    if ($farmer->province_id) {
        $zone=$farmer->getZone->name==0 ? 'مرکزی' : $farmer->getZone->name;
        $array[] = [
            'label' => 'محل سکونت - استان / شهر / منطقه  :',
            'value' => $farmer->getProvince->name.' / '.$farmer->getCity->name.' / '.$zone
        ];
    }
    if ($farmer->address) {
        $array[] = [
            'label' => 'آدرس محل سکونت :',
            'value' => replace_number($farmer->address)
        ];
    }
    if ($farmer->zip_code) {
        $array[] = [
            'label' => 'کد پستی :',
            'value' => replace_number($farmer->zip_code)
        ];
    }
    if ($farmer->more_information) {
        $array[] = [
            'label' => 'سایر اطلاعات تکمیلی :',
            'value' => replace_number($farmer->more_information)
        ];
    }

    return $array;
}
