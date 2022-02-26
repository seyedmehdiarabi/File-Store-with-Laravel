<?php


namespace Modules\discount;

use Modules\categories\Models\Category;
use Session;
use Auth;

class Module
{
    protected $parent_cat = [];

    public function discount_create_form()
    {
        if (class_exists(Category::class)) {
            $cats = Category::catList();
            view()->share('cats', $cats);
            return [
                'after_expiry_time' => 'discount::cat-list',
            ];
        }
    }

    public function discount_edit_form()
    {
        if (class_exists(Category::class)) {
            $cats = Category::catList();
            view()->share('cats', $cats);
            return [
                'after_expiry_time' => 'discount::cat-list',
            ];
        }
    }

    public function check_has_discount($discount, $variation)
    {
        if ($discount->cat_id > 0) {
            $cat_id = $discount->cat_id;
            if (!array_key_exists($cat_id, $this->parent_cat)) {
                $parent = Category::where('parent_id', $discount->cat_id)->pluck('id', 'id')->toArray();
                $parent[$cat_id] = $cat_id;
                $this->parent_cat[$cat_id] = $parent;
            }
            if (array_key_exists($variation->product->cat_id, $this->parent_cat[$cat_id])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkout_items()
    {
        $display = Session::get('discount_value', 0) > 0 ? "block" : 'none';
        return [
            'name' => 'checkout_discount',
            'title' => 'تخفیف',
            'value' => get_price(Session::get('discount_value', 0)),
            'display' => $display,
            'type' => 'deduction',
        ];
    }

    public function checkout_final_price($data)
    {
        $discount_value = Session::get('discount_value', 0);
        if ($discount_value > 0) {
            if (array_key_exists("normal", $data[1])) {
                $data[1]['normal'] = ($data[1]['normal'] - intval($discount_value));
            }
            if (array_key_exists("fast", $data[1])) {
                $data[1]['fast'] = ($data[1]['fast'] - intval($discount_value));
            }
            return $data;
        }
    }

    public function set_user_access_list($access)
    {
        $access['discount'] = [
            'label' => 'تخفیف ها',
            'access' => [
                'discount' => ['label' => 'مدیریت کد های تخفیف', 'routes' => [
                    'discount.index', 'discount.edit', 'discount.update', 'discount.create', 'discount.store',
                    'discount.destroy', 'discount.restore'
                ]],
            ]
        ];
        return $access;
    }

    public function promotion_select($promotion)
    {
        $promotion[] = ['name' => 'کد تخفیف', 'value' => 'discount']; //Don't Change It
        return $promotion;
    }

    public function payment_page()
    {
        return [
            [
                'view' => 'discount::box-view',
                'index' => 0

            ]
        ];
    }

    public function registerComponent()
    {
        $routeName = getRouteName();
        if ($routeName == 'cart_payment') {
            return veu_component_detail('discount');
        }
    }

    public function after_payment()
    {
        if (Session::has('discount_id')) {
            Session::forget('discount_value');
            Session::forget('discount_code');
            $key = 'user_' . Auth::user()->id . '_discount' . Session::get('discount_id');
            $value = cache()->get($key);
            if ($value) {
                $value = $value + 1;
                cache()->set($key, $value);
            } else {
                cache()->set($key, 1);
            }
        }
    }

    public function card_changed()
    {
        if (Session::has('discount_id')) {
            Session::forget('discount_value');
            Session::forget('discount_code');
            Session::forget('discount_id');
        }
    }

    public function order_detail($data): array
    {
        if (!empty($data['order']->discount_value)) {
            $data['detail'][] = ['label' => 'تخفیف', 'value' => get_price($data['order']->discount_value)];
            $data['detail'][] = ['label' => 'کد تخفیف', 'value' => $data['order']->discount_code];
        }
        return $data;
    }

    public function saving_order($data)
    {
        if (Session::has('discount_id')) {
            $data['discount_code'] = Session::get('discount_code');
            $data['discount_value'] = Session::get('discount_value');
        }
        return $data;
    }
}
