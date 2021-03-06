<?php


namespace App;

use App\Observers\ModuleObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        $class = get_called_class();
        if ($class) {
            $class::observe(ModuleObserver::class);
        }
    }

    public function __call($method, $parameters)
    {
        $action = $this->table . '_' . $method . '_relation';
        $result = run_action($action, [], true);
        if (is_array($result) && sizeof($result) == 1) {
            $function = $result[0];
            return $function($this);
        }
        return parent::__call($method, $parameters); // TODO: Change the autogenerated stub
    }


    public static function getTableName()
    {
        $class = get_called_class();
        return (new $class)->getTable();
    }

    public static function CPagination($request, $filters = [], $relations = [], $function = null)
    {
        $string = '?';
        $rows = self::orderBy('id', 'DESC')->with($relations);
        if (inTrashed($request)) {
            $rows = $rows->onlyTrashed();
        }
        $filters = CompleteData(self::getTableName() . '_pagination', $filters);
        foreach ($filters as $filter) {
            if (sizeof($filter) == 3) {
                if (!empty($filter[2])) {
                    if ($filter[1] == 'like') {
                        $rows = $rows->where($filter[0], 'like', '%' . $filter[2] . '%');
                    } else {
                        $rows = $rows->where($filter[0], $filter[1], $filter[2]);
                    }
                }
            } else if (sizeof($filter) == 2) {
                if (!empty($filter[1])) {
                    if (is_array($filter[1])) {
                        $rows = $rows->whereIn($filter[0], $filter[1]);
                    } else {
                        $rows = $rows->where($filter[0], $filter[1]);
                    }
                }
            }
        }
        if ($function) {
            $rows = $function($rows);
        }
        $allQuery = request()->all();
        foreach ($allQuery as $key => $value) {
            if ($key !== 'page') {
                $string = create_paginate_url($string,$key.'='.$value);
            }
        }
        $rows = $rows->paginate(10);
        $rows->withPath($string);
        return $rows;
    }
}
