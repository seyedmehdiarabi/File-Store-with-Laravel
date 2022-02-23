<?php


namespace Modules\users\Repository;


use App\Repositories\BaseInterface;

interface UsersRepositoryInterface extends BaseInterface
{
    public function first($where);

    public function add_register_detail($user,$data);

    public function all();
}
