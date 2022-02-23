<?php
namespace Modules\setting\Models;
use App\CustomModel;
use DB;
class Setting extends CustomModel
{
    protected $table='setting';

    protected $guarded=[];

    public function addConfig($request)
    {
        $config=config('shop-info');
        $data=$request->all();
        $array=['jpg', 'jpeg', 'gif', 'png', 'bmp'];
        foreach($data as $key=>$value)
        {
            if($key!="_token")
            {
                if(!empty($value))
                {
                    if($request->hasFile($key))
                    {
                        $ex=$request->file($key)->getClientOriginalExtension();
                        if(in_array($ex,$array))
                        {
                            $img_url=upload_file($request,$key,'images');
                            if($img_url!=null)
                            {
                                $config[$key]='files/images/'.$img_url;
                            }
                        }
                    }
                    else{
                        $config[$key]=$value;
                    }
                }
            }
        }

        $text='<?php

return  '.var_export($config,true).';';

        file_put_contents(config_path('shop-info.php'),$text);
    }

    public function addGatewayConfig($request){
        $config=config('gateway');
        $data=$request->all();
        unset($data['_token']);
        $text='<?php

return  '.var_export($data,true).';';
        file_put_contents(config_path('gateway.php'),$text);
    }
}
