<?php


namespace Modules\orders;

use Modules\orders\Repository\OrdersRepositoryInterface;

class Module
{
    public function admin_panel_index()
    {
        config()->set('view.orders_component', 'add');
        return [
            [
                'view' => 'orders::widgets.chart',
                'index' => 0
            ],
            [
                'view' => 'orders::widgets.last_orders',
                'index' => 5,
            ]
        ];
    }

    public function before_show_view($data)
    {
        $routeName = getRouteName();
        if ($routeName == 'admin_index') {
            $repository = app(OrdersRepositoryInterface::class);
            $data['orders'] = $repository->getList(request());
        }
        if ($routeName == 'user_profile') {
            config()->set('view.build_component', 'add');
            $user_id = \Auth::user()->id;
            $repository = app(OrdersRepositoryInterface::class);
            $lastOrder = $repository->userLastOrder(['user_id' => $user_id]);
            $data['lastOrder'] = $lastOrder;
        }
        return $data;
    }

    public function user_panel_menu($menus)
    {
        $menus[] = [
            'label' => 'سفارشات من',
            'icon' => 'mdi-format-list-bulleted',
            'url' => url('user/profile/orders')
        ];
        return $menus;
    }

    public function user_panel_index()
    {
        return [
            [
                'view' => 'orders::widgets.user_last_order',
                'index' => 2
            ]
        ];
    }

    public function shop_setting_form()
    {
        return [
            'after_shop_name' => 'orders::shop_setting'
        ];
    }

    public function registerComponent()
    {
        $routeName = getRouteName();
        if ($routeName === 'orders_show' || $routeName === 'submissions_show' || $routeName == 'user_orders'
            || $routeName == 'user_order_detail' || config('view.orders_component') == 'add'
        ) {
            return vue_component_detail('orders');
        }
    }
}
