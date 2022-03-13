<?php

namespace Modules\comments\Models;


use App\CustomModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\users\Models\User;

class Comments extends CustomModel
{
    use SoftDeletes;
    protected $table="comments";
    protected $fillable=['name','email','content','parent_id','product_id','status','user_id'];

    public function getUserInfo()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function get_child(){
        return $this->hasMany(self::class,'parent_id','id');
    }
    public function get_parent(){
        return $this->hasOne(self::class,'id','parent_id');
    }
    public function product(){
        return $this->hasOne(self::class,'id','product_id');
    }
}
