<?php

namespace Modules\products\Http\Requests;

use App\Http\Requests\CFormRequest;

class ProductRequest extends CFormRequest
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
            'title'=>'required|unique:products,title,'.$this->product,
            'price'=>'required|integer',
            'pic'=>'image|max:10240',
        ];
    }
    public function attributes()
    {
        return [
            'title'=>'عنوان محصول',
            'price'=>'هزینه محصول',
            'pic'=>'تصویر محصول'
        ];
    }
}
