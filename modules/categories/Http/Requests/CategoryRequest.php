<?php

namespace Modules\categories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'ename'=>'required|unique:category,ename,'.$this->category,
            'parent_id'=>'required'
        ];
    }
    public function attributes()
    {
        return [
            'name'=>'نام دسته',
            'ename'=>'نام انگلیسی دسته',
            'parent_id'=>'سر دسته'
        ];
    }
}
