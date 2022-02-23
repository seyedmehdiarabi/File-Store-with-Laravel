<?php


namespace App\Repositories;


interface BaseInterface
{
    public function restore($id);
    public function find($id);
    public function create($request);
    public function trashCount();
    public function getList($request);
    public function update($id,$request);
    public function destroy($id);
    public function removeItems($ids);
    public function restoreItems($ids);
}
