<?php


namespace Modules\products\Repository;


use App\Repositories\EloquentBaseRepository;
use Modules\products\Models\Product;

class EloquentProductRepository extends EloquentBaseRepository implements ProductRepositoryInterface
{

    protected $model='Modules\products\Models\Product';

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function create($request)
    {
        $fake=$request->has('fake') ? 1 : 0;
        $product=new Product($request->all());
        $image_url=upload_file($request,'pic','products');
        $product->pic=$image_url;
        $product->view=0;
        $product->saveOrFail();
    }

    public function trashCount()
    {
        return Product::onlyTrashed()->count();
    }

    public function getList($request)
    {
        return  Product::getData($request->all());
    }

    public function update($id, $request)
    {
        $data=$request->all();
        $fake=$request->has('fake') ? 1 : 0;
        $product=Product::findOrFail($id);
        $image_url=upload_file($request,'pic','products');
        if (!empty($image_url))
        {
            remove_file($product->pic,'products');
            $product->pic=$image_url;
        }
        $product->update($data);
    }
    public function firstOrFail($id,$select)
    {
        return Product::where('id',$id)->select($select)->firstorFail();
    }

    public function findForPage($product_id, $product_url)
    {
        $id=str_replace('dkp-','',$product_id);
        $where=['id'=>$id];
        if($product_url!=null)
        {
            $where['product_url']=$product_url;
        }

        $product_relation=CompleteData('show_product_relation',[]);

        return Product::with($product_relation)->where($where)->firstOrFail();

    }

    public function findWithWhere($where,$relation=[]){
        $product=Product::where($where);
        if(is_array($relation) && sizeof($relation)>0){
            $product=$product->with($relation);
        }
        $product=$product->first();

        return $product;
    }

   public function get($where=[],$whereIn=[],$whereKey=null,$select=[]){
       $query=Product::where($where);
       if(sizeof($whereIn)>0 && $whereKey!=null){
           $query=$query->whereIn($whereKey,$whereIn);
       }
       if(sizeof($select)>0){
           $query=$query->select($select);
       }
       return $query=$query->get();
   }

    public function first($where = [], $relation = [])
    {
        $product=Product::where($where);

        return $product->first();
    }
}
