<?php


namespace Modules\users;

use Modules\users\Repository\AdditionalInfoRepositoryInterface;
use Modules\users\Repository\UsersRepositoryInterface;
use View;

class Module
{
    protected $user_access = null;

    public function registerComponent()
    {
        return vue_component_detail('users');
    }

    public function users_index_action()
    {
        return function ($model) {
            $url = url('users/' . $model->id . '/additional_info');
            return view('users::additionalInfo.additional_info_icon', ['url' => $url]);
        };
    }

    public function getUserAccess()
    {
        if ($this->user_access == null) {
            $this->user_access = getAllAccess();
            return $this->user_access;
        } else {
            View::share('access', $this->user_access);
            return $this->user_access;
        }
    }

    public function set_user_access_list($access)
    {

        $access['users'] = [
            'label' => 'کاربران',
            'access' => [
                'discount_edit' => ['label' => 'ثبت و ویرایش کاربر', 'routes' => [
                    'users.index', 'users.create', 'users.store', 'users.edit', 'users.update'
                ]],
                'remove_users' => ['label' => 'حذف کاربر', 'routes' => ['users.index', 'users.destroy']],
                'restore_users' => ['label' => 'بازیابی کاربر', 'routes' => ['users.index', 'users.restore']]
            ]
        ];
        $access['admin'] = [
            'label' => 'پیشخوان',
            'access' => [
                'admin_index' => ['label' => 'پیشخوان', 'routes' => [
                    'admin.index'
                ]],
            ]
        ];

        return $access;
    }

    public function user_panel_index()
    {
        if (view_type == '') {
            return [
                [
                    'index' => 0,
                    'view' => 'users::additionalInfo.register-detail'
                ]
            ];
        }
    }

    public function before_show_view($args)
    {
        $routeName = getRouteName();
        if ($routeName == 'users_profile' || $routeName == 'users_show') {
            $repository = app(AdditionalInfoRepositoryInterface::class);
            $additionalInfo = $repository->first(request());
            $args['additionalInfo'] = $additionalInfo;
            $args['personal_user_detail'] = personal_user_detail($additionalInfo);
        }
        if ($routeName == 'land_index' || $routeName == 'land_create' || $routeName == 'land_edit' || $routeName == 'visit_index' || $routeName == 'visit_create' || $routeName == 'visit_edit') {
            $repository = app(UsersRepositoryInterface::class);
            $farmer = $repository->getFarmer();
            View::share('farmer', $farmer);
        }
        return $args;
    }

    public function registered($user)
    {
        $code = $user->active_code;
        send_auth_sms($user, $code);
    }

    public function desktop_layout()
    {
        return [
            [
                'view' => 'users::auth.login-dialog',
                'index' => 0
            ]
        ];
    }

    public function mobile_layout()
    {
        return [
            [
                'view' => 'users::auth.mobile-login-box',
                'index' => 0
            ]
        ];
    }
}
