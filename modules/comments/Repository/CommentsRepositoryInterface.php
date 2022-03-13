<?php


namespace Modules\comments\Repository;


use App\Repositories\BaseInterface;

interface CommentsRepositoryInterface extends BaseInterface
{
    public function catList() :array;

    public function all();

}
