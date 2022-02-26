<?php

namespace Modules\products\Http\Controllers;

use App\Http\Controllers\Admin\CustomController;
use Illuminate\Http\Request;
use DB;
use Modules\products\Http\Requests\ProductRequest;
use Modules\products\Repository\ProductRepositoryInterface;

class ProductController extends CustomController
{
    protected $title='محصول';

    protected $route_params='admin/products';

    protected $repository=null;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository=$repository;
    }

    public function index(Request $request)
    {
        $product=$this->repository->getList($request);
        $trash_product_count=$this->repository->trashCount();
        return CView('products::index',['product'=>$product,'trash_product_count'=>$trash_product_count,'req'=>$request]);
    }

    public function create()
    {
        return CView('products::create');
    }
    public function store(ProductRequest $request)
    {
        $this->repository->create($request);
        return  [
            'redirect_url'=>url('admin/products'),
            'message'=>'ثبت محصول با موفقیت انجام شد'
        ];
    }

    public function edit($id)
    {
        $product=$this->repository->find($id);
        return CView('products::edit',compact('product'));
    }

    public function update(ProductRequest $request, $id)
    {
        $this->repository->update($id,$request);
        return  [
            'redirect_url'=>url('admin/products'),
            'message'=>'ویرایش محصول با موفقیت انجام شد'
        ];
    }

}
