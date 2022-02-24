<?php

use Modules\categories\Models\Category;

function get_horizontal_product_list_setting()
{

    $sort = [
        0 => 'جدید ترین',
        1 => 'پر فروش ترین',
        2 => 'پر بازدید ترین'
    ];
    $product_type = [
        0 => 'دسته محصول',
        1 => 'علاقه مندی های کاربر',
        2 => 'محصولات موجود در سبد خرید'
    ];
    $arr = [
        'title' => ['type' => 'string', 'value' => '', 'label' => 'عنوان'],
        'sort' => ['label' => 'مرتب سازی بر اساس', 'type' => 'array', 'value' => 0],
        'product_type' => [
            'label' => 'نمایش محصول بر اساس',
            'value' => '',
            'type' => 'array',
        ],
    ];
    $arr['catList'] = [
        'type' => 'string', 'value' => '',
        'label' => 'شناسه دسته'
    ];
    return [
        'list' => $arr,
        'data' => ['sort' => $sort,'product_type' => $product_type]
    ];
}
