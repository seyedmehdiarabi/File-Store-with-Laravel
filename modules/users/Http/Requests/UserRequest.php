<?php

namespace Modules\users\Http\Requests;

use App\Http\Requests\CFormRequest;
use Modules\users\Rules\ValidateMobileNumber;

class UserRequest extends CFormRequest
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
            'mobile'=>['required',new ValidateMobileNumber($this->user)]
        ];
        if($this->role=='admin')
        {
            $rules['username']=['required','unique:users,username,'.$this->user.''];
        }
        if($this->method()=="POST")
        {
            $rules['name']=['required'];
            $rules['password']=['required','min:6'];
        }
        return $rules;
    }
}
