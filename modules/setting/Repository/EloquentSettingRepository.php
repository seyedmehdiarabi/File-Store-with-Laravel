<?php


namespace Modules\setting\Repository;



use Modules\setting\Models\Setting;

class EloquentSettingRepository  implements SettingRepositoryInterface
{
    public function getValues($keys){
        $setting=new Setting();
        $values=$this->get_data($keys);
        return $values;
    }

    public function set_data($data)
    {
        $update_data=array();
        foreach ($data as $key=>$value)
        {
            if($key!="_token")
            {
                $this->add_table($key,$value);
                $update_data[$key]=$value;
            }
        }
        return $update_data;
    }

    public function get_data($keys)
    {
        $fetch_data=array();
        $fetch_data=Setting::whereIn('option_name',$keys)->pluck('option_value','option_name')->toArray();
        foreach ($keys as $k)
        {
            if(!array_key_exists($k,$fetch_data)){
                $fetch_data[$k]='';
            }
        }
        return $fetch_data;
    }

    protected function add_table($key,$value){
        $row=Setting::where('option_name',$key)->first();
        if($row)
        {
            if(!empty($value))
            {
                Setting::where('option_name',$key)->update(['option_value'=>$value]);
            }
            else{
                Setting::where('option_name',$key)->delete();
            }
        }
        else{
            if(trim($value)!=='')
            {
                Setting::create([
                    'option_name'=>$key,
                    'option_value'=>strval($value)
                ]);
            }
        }
    }

    public function get_value($option_name)
    {
        $row=Setting::where('option_name',$option_name)->first();
        if($row)
        {
            return $row->option_value;
        }
        else{
            return  '';
        }
    }
}
