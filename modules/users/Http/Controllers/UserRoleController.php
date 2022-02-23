<?php

namespace Modules\users\Http\Controllers;

use App\Http\Controllers\Admin\CustomController;
use Illuminate\Http\Request;
use DB;
use Modules\users\Repository\UserRoleRepositoryInterface;

class UserRoleController extends CustomController
{
    protected $title='نقش کاربری';

    protected $route_params='userRole';

    protected $repository;

    public function __construct(UserRoleRepositoryInterface $repository)
    {
        $this->repository=$repository;
    }

    public function index(Request $request)
    {
        $userRole=$this->repository->getList($request);
        $trash_role_count=$this->repository->trashCount();
        return CView('users::roles.index',['userRole'=>$userRole,'trash_role_count'=>$trash_role_count,'req'=>$request]);
    }

    public function create()
    {
        return CView('users::roles.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,['name'=>'required|unique:users__roles'],[],['role_name'=>'نام نقش کاربری']);
        $this->repository->create($request);
        return [
            'redirect_url'=>url('userRole'),
            'message'=>'ثبت نقش کاربری با موفقیت انجام شد'
        ];
    }
    public function edit($id)
    {
        $userRole=$this->repository->find($id);
        return CView('users::roles.edit',['userRole'=>$userRole]);
    }
    public function update($id,Request $request)
    {
        $this->validate($request,['name'=>'required|unique:users__roles,name,'.$id],[],['role_name'=>'نام نقش کاربری']);
        $this->repository->update($id,$request);
        return [
            'redirect_url'=>url('userRole'),
            'message'=>'ویرایش نقش کاربری با موفقیت انجام شد'
        ];
    }
    public function access($role_id)
    {
        $role=$this->repository->find($role_id);
        $role_accesses=$this->repository->getAccess($role_id);
        return CView('users::roles.access',['role'=>$role,'role_accesses'=>$role_accesses]);
    }
    public function add_access($role_id,Request $request)
    {
        $this->repository->find($role_id);
        $this->repository->setAccess($role_id,$request);
        return redirect()->back()->with('message','ثبت دسترسی با موفقیت انجام شد');
    }
}
