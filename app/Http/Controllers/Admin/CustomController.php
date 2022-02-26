<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use View;
class CustomController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function destroy($id)
    {
        $query_string=property_exists($this,'query_string') ? '&'.$this->query_string : '';
        $result=$this->repository->destroy($id);
        if($result=='delete'){
            $message="$this->title  انتخاب شده به سطل زباله انتقال یافت";
        }
        else{
            $message="$this->title  انتخاب شده حذف شد";
        }

        return  [
            'redirect_url'=>url($this->route_params.'?trashed=true'.$query_string),
            'message'=>$message
        ];

    }

    public function remove_items(Request $request)
    {
        $query_string=property_exists($this,'query_string') ? '&'.$this->query_string : '';
        $param_name=$this->route_params.'_id';
        $ids=$request->get($param_name,array());
        $result=$this->repository->removeItems($ids);
        if($result=='delete'){
            $message="$this->title های انتخاب شده به سطل زباله انتقال یافت";
        }
        else{
            $message="$this->title های انتخاب شده حذف شد";
        }

        return  [
            'redirect_url'=>url($this->route_params.'?trashed=true'.$query_string),
            'message'=>$message
        ];
    }

    public function restore_items(Request $request)
    {
        $query_string=property_exists($this,'query_string') ? '&'.$this->query_string : '';
        $param_name=$this->route_params.'_id';
        $ids=$request->get($param_name,array());
        $this->repository->restoreItems($ids);
        $message="بازیابی $this->title ها با موفقیت انجام شد";

        return  [
            'redirect_url'=>url($this->route_params.'?trashed=true'.$query_string),
            'message'=>$message
        ];
    }

    public function restore($id)
    {
        $query_string=property_exists($this,'query_string') ? '&'.$this->query_string : '';
        $this->repository->restore($id);
        $message="بازیابی $this->title  با موفقیت انجام شد";
        return  [
            'redirect_url'=>url($this->route_params.'?trashed=true'.$query_string),
            'message'=>$message
        ];
    }
}
