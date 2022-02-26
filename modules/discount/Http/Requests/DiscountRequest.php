<?php

namespace Modules\discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
        return [
            'name'=>'required',
            'value'=>'required|integer',
        ];
    }
    public function attributes()
    {
        return [
            'name'=>'نام کد تخفیف',
            'value'=>'مقدار تخفیف',
        ];
    }
}
