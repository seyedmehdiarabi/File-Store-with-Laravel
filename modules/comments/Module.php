<?php


namespace Modules\comments;


class Module
{
    public function registerComponent()
    {
        $routeName = getRouteName();
        if ($routeName == 'shop_show_product'|| $routeName==='comment_index') {
            return vue_component_detail('comments');
        }
    }}
