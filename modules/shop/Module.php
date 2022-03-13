<?php


namespace Modules\shop;


class Module
{
    public function registerComponent()
    {
        return vue_component_detail('shop');
    }
}
