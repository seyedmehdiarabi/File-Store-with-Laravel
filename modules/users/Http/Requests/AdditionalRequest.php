<?php

namespace Modules\users\Http\Requests;

use App\Http\Requests\CFormRequest;
use Modules\users\Rules\BankCode;
use Modules\users\Rules\NationalCode;
use Modules\users\Rules\ValidateMobileNumber;

class AdditionalRequest extends CFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules=[
            'first_name'=>['required'],
            'last_name'=>['required'],
            'national_identity_number'=>['required',new NationalCode()],
            'mobile_phone'=>['required',new ValidateMobileNumber()]
        ];
        if(!empty($this->bank_card_number))
        {
            $rules['bank_card_number']=['string','size:16',new BankCode()];
        }
        if(!empty($this->email)){
            $rules['email']=['required','email'];
        }
        if($this->Legal=='true')
        {
            $rules['company_name']=['required'];
            $rules['company_economic_number']=['required'];
            $rules['company_national_identity_number']=['required','numeric','digits:11'];
            $rules['company_registration_number']=['required'];
            $rules['company_phone']=['required'];
            $rules['city_id']=['required'];
            $rules['province_id']=['required'];
        }
        return $rules;
    }
    public function attributes()
    {
        return [
            'national_identity_number'=>'کد ملی',
            'bank_card_number'=>'شماره کارت',
            'company_name'=>'نام شرکت',
            'company_economic_number'=>'کد اقتصادی',
            'company_national_identity_number'=>'شناسه ملی',
            'company_registration_number'=>'شماره ثبت',
            'company_phone'=>'شماره تلفن ثابت',
            'city_id'=>'شهر',
            'province_id'=>'استان',
        ];
    }
}
