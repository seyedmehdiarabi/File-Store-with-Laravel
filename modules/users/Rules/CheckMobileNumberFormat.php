<?php

namespace Modules\users\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckMobileNumberFormat implements Rule
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
        settype($value, 'integer');
        if (strlen($value) == 10 && is_numeric($value) && substr($value, 0, 1) == "9") {
            return true;
        } else {
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
