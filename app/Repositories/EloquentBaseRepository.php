<?php


namespace App\Repositories;


class EloquentBaseRepository
{
    public function restore($id)
    {
        $row=$this->model::withTrashed()->where('id',$id)->firstOrFail();
        $row->restore();
    }
    public function destroy($id):string {
        $row=$this->model::withTrashed()->findOrFail($id);
        if($row->deleted_at==null){
            $row->delete();
            return 'delete';
        }
        else{
            $row->forceDelete();
            return 'forceDelete';
        }
    }
    public function removeItems($ids){
        $removeType='delete';
        foreach ($ids as $key=>$value)
        {
            $row=$this->model::withTrashed()->where('id',$value)->firstOrFail();
            if($row->deleted_at==null){
                $row->delete();
            }
            else{
                $row->forceDelete();
                $removeType='forceDelete';
            }
        }
        return $removeType;
    }
    public function restoreItems($ids){
        foreach ($ids as $key=>$value)
        {
            $row=$this->model::withTrashed()->where('id',$value)->firstOrFail();
            $row->restore();
        }
    }
}
