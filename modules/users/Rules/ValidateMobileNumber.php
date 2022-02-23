<?php

namespace Modules\users\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use Log;
use Modules\users\Models\User;

class ValidateMobileNumber implements Rule
{
    protected $user_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_id=0)
    {
        $this->user_id=$user_id;
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
        $user_id=$this->user_id==0 ? (Auth::check() ? Auth::user()->id : 0) : $this->user_id;
        settype($value,'integer');
        if(strlen($value)==10 && is_numeric($value) && substr($value,0,1)=="9")
        {
            $check=User::whereIn('mobile',[$value,"0".$value])->first();
            if($check && $check->id!=$user_id)
            {
                if($check->account_status=='InActive')
                {
                    if($check->forceDelete())
                    {
                        return true;
                    }
                    else{
                        return  false;
                    }
                }
                else{
                    return  false;
                }
            }
            else{
                return true;
            }
        }
        else
        {
            return  false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'شماره موبایل وارد شده معتبر نمی باشد';
    }
}
