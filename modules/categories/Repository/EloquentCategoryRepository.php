<?php


namespace Modules\categories\Repository;


use App\Repositories\EloquentBaseRepository;
use Illuminate\Support\Facades\DB;
use Modules\categories\Models\Category;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepositoryInterface
{
    protected  $model='Modules\categories\Models\Category';

    public function find($id)
    {
       return Category::findOrFail($id);
    }

    public function create($request)
    {
        $Category=new Category($request->all());
        $img_url=upload_file($request,'pic','upload');
        $Category->img=$img_url;
        $Category=$Category->attributesToArray();
        $Category['created_at']=now();
        $Category['updated_at']=now();
        DB::table('category')->insert($Category);
        //$Category->save();
    }

    public function trashCount()
    {
        return Category::onlyTrashed()->count();
    }

    public function getList($request)
    {
        return Category::getData($request->all());
    }

    public function update($id, $request)
    {
        $data = $request->all();
        $category=Category::findOrFail($id);
        $img_url=upload_file($request,'pic','upload');
        if($img_url!=null){
            remove_file($category->img,'upload');
            $data['img']=$img_url;
        }
        unset($data['pic']);
        unset($data['_method']);
        $data['updated_at']=now();
        DB::table('category')->where('id',$category->id)->update($data);
        //$category->update($data);
    }

    public function catList(): array
    {
        return  Category::catList();
    }

    public function all()
    {
        return Category::with('getChild.getChild.getChild')
            ->where('parent_id',0)->get();
    }

    public function onlyFind($id)
    {
        return Category::find($id);
    }

    public function catList2(): array
    {
        return  Category::catList2();
    }

    public function firstWithRelation($relation, $where)
    {
        return Category::with($relation)
            ->where($where)->first();
    }

    public function getWithIds($ids,$key)
    {
        return Category::whereIn($key,$ids)->get();
    }

    public function getWithRelation($relation,$where){
        $categories=Category::with($relation);
        if(sizeof($where)>0){
            $categories=$categories->where($where);
        }
        return $categories->get();
    }

    public function getArrayCategoriesId($where): array
    {
        $array=[];
        $categories=Category::where($where)->with('getChild.getChild.getChild')->first();
        if($categories){
            $array[]=$categories->id;
            foreach ($categories->getChild as $child1){
                $array[]=$child1->id;

                foreach ($child1->getChild as $child2){
                    $array[]=$child2->id;

                    foreach ($child2->getChild as $child3){
                        $array[]=$child3->id;
                    }
                }
            }
        }
        return $array;
    }
}
