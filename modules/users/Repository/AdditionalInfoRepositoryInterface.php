<?php


namespace Modules\users\Repository;


interface AdditionalInfoRepositoryInterface
{
    public function first($request);

    public function addUserInfo($user,$request);

    public function find($where);

    public function  update_mobile($request);
}
