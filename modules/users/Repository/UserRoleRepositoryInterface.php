<?php


namespace Modules\users\Repository;


use App\Repositories\BaseInterface;

interface UserRoleRepositoryInterface extends BaseInterface
{
    public function getAccess($role_id);

    public function setAccess($role_id,$request);

    public function all() :array ;

    public function allAccess($role_id);
}
