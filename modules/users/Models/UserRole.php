<?php

namespace Modules\users\Models;

use App\CustomModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class   UserRole extends CustomModel
{
    use SoftDeletes;
    protected $table='users__roles';
    protected $guarded=[];
    public static function getData($request)
    {
        $string='?';
        $userRole=self::withCount('userrole')->orderBy('id','DESC');
        if(inTrashed($request))
        {
            $userRole=$userRole->onlyTrashed();
            $string=create_paginate_url($string,'trashed=true');
        }
        $userRole=$userRole->paginate(10);
        $userRole->withPath($string);
        return $userRole;
    }
    public function userrole()
    {
        return $this->hasMany(User::class,'role_id','id');
    }
}
