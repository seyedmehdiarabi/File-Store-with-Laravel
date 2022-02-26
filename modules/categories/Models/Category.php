<?php

namespace Modules\categories\Models;


use App\CustomModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends CustomModel
{
    use SoftDeletes;
    protected $table='category';
    protected $guarded=['pic'];

    public static function catList()
    {
        $array=[0=>'دسته اصلی'];
        $list=self::with('getChild.getChild')->where('parent_id',0)->get();
        foreach ($list as $key=>$value)
        {
            $array[$value->id]=$value->name;
            foreach ($value->getChild as $key2=>$value2)
            {
                $array[$value2->id]=' - '.$value2->name;
                foreach ($value2->getChild as $key3=>$value3)
                {
                    $array[$value3->id]=' - - '.$value3->name;
                }
            }
        }
        return $array;
    }

    public static function catList2()
    {
        $array=[''=>'انتخاب دسته'];
        $list=self::with('getChild.getChild.getChild')->where('parent_id',0)->get();
        foreach ($list as $key=>$value)
        {
            $array[$value->id]=$value->name;
            foreach ($value->getChild as $key2=>$value2)
            {
                $array[$value2->id]=' - '.$value2->name;
                foreach ($value2->getChild as $key3=>$value3)
                {
                    $array[$value3->id]=' - - '.$value3->name;

                    foreach ($value3->getChild as $key4=>$value4)
                    {
                        $array[$value4->id]=' - - - '.$value4->name;
                    }
                }
            }
        }
        return $array;
    }

    public function getChild()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }

    public function getParent()
    {
        return $this->hasOne(Category::class,'id','parent_id')
            ->withTrashed()->withDefault(['name'=>'-']);
    }

    public static function getData($request)
    {
        $string='?';
        $category=self::with('getParent');
        if(inTrashed($request))
        {
            $category=$category->onlyTrashed();
            $string=create_paginate_url($string,'trashed=true');
        }
        if(array_key_exists('string',$request) && !empty($request['string']))
        {
            $category=$category->where('name','like','%'.$request['string'].'%');
            $category=$category->orWhere('ename','like','%'.$request['string'].'%');
            $string=create_paginate_url($string,'string='.$request['string']);
        }
        $category=$category->orderBy('id','DESC')->paginate(10);
        $category->withPath($string);
        return $category;
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($category){
            cache()->forget('catList');
            foreach ($category->getChild()->withTrashed()->get() as $cat)
            {
                if($category->isForceDeleting())
                {
                    $cat->forceDelete();
                }
                else{
                    $cat->delete();
                }
            }
        });
        static::restoring(function ($category){
            cache()->forget('catList');
            foreach ($category->getChild()->withTrashed()->get() as $cat)
            {
                $cat->restore();
            }
        });
    }

}
