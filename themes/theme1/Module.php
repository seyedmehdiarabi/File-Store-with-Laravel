<?php

namespace themes\theme1;

use Modules\comments\Models\Comment;
use Modules\products\Models\Product;
use Modules\questions\Models\Question;
use themes\theme1\ProductList;

class Module
{
    protected $horizontal_widget_data = [];


    public function registerComponent()
    {
        if (!request()->is('admin/*') && !request()->is('admin')) {
            return vue_component_detail('theme1');
        }
    }


}
