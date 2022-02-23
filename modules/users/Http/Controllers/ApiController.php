<?php

namespace Modules\users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\users\Models\User;
use Modules\users\Repository\AdditionalInfoRepositoryInterface;
use Modules\users\Repository\UsersRepositoryInterface;
use Auth;

class ApiController extends Controller
{
    protected $userRepository;
    protected $request;

    public function __construct(Request $request, UsersRepositoryInterface $repository)
    {
        $this->userRepository = $repository;
        $this->request = $request;
    }

    public function add_register_detail()
    {
        $user = $this->request->user();
        $this->userRepository->add_register_detail($user, $this->request->all());
        return 'ok';
    }

    public function update_mobile(Request $request, AdditionalInfoRepositoryInterface $repository)
    {
        $user = $request->user();
        $mobile = $request->get('mobile', '');
        $value = $mobile;
        settype($value, 'integer');
        if ($user->mobile === $mobile) {
            return 'شماره موبایل قبلا توسط شما ثبت شده';
        } else if (strlen($value) == 10 && is_numeric($value) && substr($value, 0, 1) == 9) {
            $check = User::whereIn('mobile', [$value, "0" . $value])->first();
            if ($check && $check->id != $user->id) {
                return 'شماره موبایل توسط شخص دیگری ثبت شده';
            } else {
                $repository->update_mobile($request);
                return 'ok';
            }
        } else {
            return 'شماره موبایل وارد شده اشتباه می باشد';
        }
    }

    public function changeMobileNumber(Request $request, AdditionalInfoRepositoryInterface $additionalInfoRepository)
    {
        $mobile = $request->get('mobile');
        $active_code = $request->get('active_code');
        $user = $request->user();
        $additionalInfo = $additionalInfoRepository->find([
            'user_id' => $user->id,
            'mobile_phone' => $mobile
        ]);
        $result = 'خطا در ارسال اطلاعات،مجددا تلاش نمایید';
        if ($additionalInfo) {
            if ($user->active_code == $active_code) {
                $user->active_code = null;
                $user->mobile = $mobile;
                $user->update();
                $result = 'ok';
            } else {
                $result = 'کد فعال سادی اشتباه می باشد';
            }
        }
        return $result;
    }

    public function resend()
    {
        $result = 'error';
        $code = rand(99999, 1000000);
        $forget_password = $this->request->get('forget_password', 'no');
        $mobile = $this->request->get('mobile');
        if (!Auth::check()) {
            $user = $this->userRepository->first(['mobile' => $mobile]);
            if ($user) {
                if ($user->account_status == 'InActive') {
                    $user->active_code = $code;
                    $result = 'ok';
                } else if ($user->account_status == 'active' && $forget_password) {
                    $user->forget_password_code = $code;
                    $result = 'ok';
                }
            }
        } else {
            $user = $this->request->user();
            if ($user->mobile != $mobile) {
                $user->active_code = $code;
                $result = 'ok';
            }
        }
        if ($result == 'ok') {
            $user->update();
            send_auth_sms($user, $code, $mobile);
        }

        return $result;
    }

    public function bankCardUpdate()
    {
        $user = $this->request->user();
        $code = $this->request->get('bank_card_number');
        $code = replace_number2($code);
        if (empty($code) || !validateBankCard($code)) {
            return 'شماره کارت وارد شده صحیح نمی باشد';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateEmail()
    {
        $user = $this->request->user();
        $email = $this->request->get('email');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'آدرس ایمیل وارد شده صحیح نمی باشد';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateNationalIdentityNumber()
    {
        $user = $this->request->user();
        $national_identity_number = $this->request->get('national_identity_number');
        if (empty($national_identity_number) || !validateNationalIdentityNumber($national_identity_number)) {
            return 'کد ملی وارد شده صحیح نمی باشد';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateDateOfBirth()
    {
        $user = $this->request->user();
        $date_of_birth = $this->request->get('date_of_birth');
        if (empty($date_of_birth) || !validateData($date_of_birth)) {
            return 'تاریخ تولد وارد شده صحیح نمی باشد';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateCeremonialTitle()
    {
        $user = $this->request->user();
        $ceremonial_title = $this->request->get('ceremonial_title');
        if (empty($ceremonial_title)) {
            return 'عنوان تشریفاتی وارد شده صحیح نمی‌باشد.';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateIdNumber()
    {
        $user = $this->request->user();
        $id_number = $this->request->get('id_number');
        if (empty($id_number)) {
            return 'شماره شناسنامه وارد شده صحیح نمی‌باشد.';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updatePhone()
    {
        $user = $this->request->user();
        $phone = $this->request->get('phone');
        if (empty($phone)) {
            return 'شماره تلفن وارد شده صحیح نمی‌باشد.';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateReagent(){
        $user = $this->request->user();
        $reagent = $this->request->get('reagent');
        if (empty($reagent)) {
            return 'اطلاعات وارد شده صحیح نمی‌باشد!';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

    public function updateIntroduction(){
        $user = $this->request->user();
        $introduction = $this->request->get('introduction');
        if (empty($introduction)) {
            return 'اطلاعات وارد شده صحیح نمی‌باشد!';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }

     public function updateDescription(){
        $user = $this->request->user();
        $description = $this->request->get('description');
        if (empty($description)) {
            return 'اطلاعات وارد شده صحیح نمی‌باشد!';
        } else {
            $this->userRepository->add_register_detail($user, $this->request->all());
            return 'ok';
        }
    }
    public function farmers(){
            return $this->userRepository->all();
    }
}
