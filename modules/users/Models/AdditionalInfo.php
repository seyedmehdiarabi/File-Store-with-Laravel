<?php

namespace Modules\users\Models;

use App\CustomModel;
use Modules\city\Models\City;
use Modules\province\Models\Province;

class AdditionalInfo extends CustomModel
{
    protected $table='users__additional_info';

    protected $guarded=['Legal'];

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','id')
            ->withDefault(['name'=>'']);
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id')
            ->withDefault(['name'=>'']);
    }
}
