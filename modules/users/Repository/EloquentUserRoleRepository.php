<?php


namespace Modules\users\Repository;


use App\Repositories\EloquentBaseRepository;
use Modules\users\Models\RoleAccess;
use Modules\users\Models\UserRole;
use DB;
class EloquentUserRoleRepository extends EloquentBaseRepository implements UserRoleRepositoryInterface
{

    protected $model='Modules\users\Models\UserRole';

    public function find($id)
    {
       return UserRole::findOrFail($id);
    }

    public function create($request)
    {
        $userRole=new UserRole($request->all());
        $userRole->saveOrFail();
    }

    public function trashCount()
    {
        return UserRole::onlyTrashed()->count();
    }

    public function getList($request)
    {
       return UserRole::getData($request->all());
    }

    public function update($id, $request)
    {
        $userRole=UserRole::findOrFail($id);
        $userRole->update($request->all());
    }

    public function getAccess($role_id)
    {
        return RoleAccess::where('role_id',$role_id)->first();
    }

    public function setAccess($role_id,$request)
    {
        $access=$request->get('access',array());
        DB::table('role_accesses')->where('role_id',$role_id)->delete();

        $string=json_encode($access);
        DB::table('role_accesses')->insert(['role_id'=>$role_id,'access'=>$string]);
    }

    public function all() :array
    {
        return UserRole::pluck('name','id')->toArray();
    }

    public function allAccess($role_id)
    {
        $row=DB::table('role_accesses')->where('role_id',$role_id)->first();
        if($row){
            return $row->access;
        }
        else{
            return  null;
        }
    }
}
