<?php


namespace Modules\orders\Http\Controllers;


use App\Http\Controllers\Admin\CustomController;
use Illuminate\Http\Request;
use Modules\address\Repository\AddressRepositoryInterface;
use Modules\orders\Repository\OrdersRepositoryInterface;
use Modules\orders\Repository\SubmissionRepositoryInterface;
use Modules\users\Repository\AdditionalInfoRepositoryInterface;

class OrdersController extends CustomController
{
    protected $title='سفارشات';

    protected $route_params='orders';

    protected $repository;

    public function __construct(OrdersRepositoryInterface $repository)
    {
        $this->repository=$repository;
    }

    public function index(Request $request)
    {
        $orders=$this->repository->getList($request);
        $trash_orders_count=$this->repository->trashCount();
        return CView('orders::index',[
            'orders'=>$orders,'trash_orders_count'=>$trash_orders_count,'req'=>$request
        ]);
    }

    public function show($id){
        $order=$this->repository->find($id);
        return CView('orders::show',compact('order'));
    }
    }
