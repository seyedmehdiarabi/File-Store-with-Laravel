<?php


namespace Modules\setting\Repository;



interface SettingRepositoryInterface
{
    public function getValues($keys);

    public function set_data($data);

    public function get_data($keys);

    public function get_value($option_name);
}
