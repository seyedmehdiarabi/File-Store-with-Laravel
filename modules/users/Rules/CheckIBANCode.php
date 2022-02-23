<?php

namespace Modules\users\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIBANCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(strlen($value)==26)
        {
           $a=substr($value,0,4);
           $value=str_replace($a,'',$value);
           $value=$value.$a;
           $value=str_replace('R',27,$value);
           $value=str_replace('I',18,$value);
           $res=bcmod($value,'97');
           settype($res,'integer');
           if($res==1){
               return true;
           }
           else{
              return false;
           }
        }
        else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'شماره شبا وارد شده معتبر نمی باشد';
    }
}
