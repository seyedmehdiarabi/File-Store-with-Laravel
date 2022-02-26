<?php


namespace Modules\discount\Repository;


use App\Repositories\EloquentBaseRepository;
use Modules\discount\Models\Discount;
class EloquentDiscountRepository extends EloquentBaseRepository implements  DiscountRepositoryInterface
{
    protected $model='Modules\discount\Models\Discount';

    public function find($id)
    {
        return Discount::findOrFail($id);
    }

    public function create($request)
    {
        $discount=new Discount($request->all());
        $discount->saveOrFail();
    }

    public function trashCount()
    {
        return Discount::onlyTrashed()->count();
    }

    public function getList($request)
    {
        return Discount::getData($request->all());
    }

    public function update($id, $request)
    {
        $discount=$this->find($id);
        $formData=$request->all();
        $discount->update($formData);
    }
}
