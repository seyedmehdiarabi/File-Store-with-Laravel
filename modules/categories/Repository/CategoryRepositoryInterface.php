<?php


namespace Modules\categories\Repository;


use App\Repositories\BaseInterface;

interface CategoryRepositoryInterface extends BaseInterface
{
    public function catList() :array;

    public function all();

    public function onlyFind($id);

    public function catList2() :array;

    public function firstWithRelation($relation,$where);

    public function getWithIds($ids,$key);

    public function getWithRelation($relation,$where);

    public function getArrayCategoriesId($where) :array;
}
