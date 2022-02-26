<?php


namespace Modules\categories;

use Modules\categories\Repository\CategoryRepositoryInterface;

class Module
{


    public function category_saving($model)
    {
        if (request()->has('name')) {
            cache()->forget('catList');
        }
    }

    public function product_form_right_box()
    {
        return 'categories::select_list';
    }

    public function before_show_view()
    {
        $routeName = getRouteName();
        if ($routeName == 'products_create' || $routeName == 'products_edit') {
            $repository = app(CategoryRepositoryInterface::class);
            $catList = $repository->catList2();
            view()->share('catList', $catList);
        }
    }

    public function set_user_access_list($access)
    {
        $access['category'] = [
            'label' => 'دسته بندی',
            'access' => [
                'category_edit' => ['label' => 'ثبت و ویرایش دسته بندی', 'routes' => [
                    'category.index', 'category.create', 'category.store', 'category.edit', 'category.update'
                ]],
                'remove_category' => ['label' => 'حذف دسته', 'routes' => ['category.index', 'category.destroy']],
                'restore_category' => ['label' => 'بازیابی دسته', 'routes' => ['category.index', 'category.restore']]
            ]
        ];

        return $access;
    }
}
