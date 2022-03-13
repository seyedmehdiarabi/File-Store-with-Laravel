<?php

namespace Modules\comments\Http\Controllers;

use App\Http\Controllers\Admin\CustomController;
use Illuminate\Http\Request;
use Modules\comments\Models\Comments;
use Modules\comments\Repository\CommentsRepositoryInterface;
use Modules\products\Models\Product;


class CommentController extends CustomController
{
    protected $title='کامنت';
    protected $route_params='admin/comments';
    protected $repository=null;
    public function __construct(CommentsRepositoryInterface $repository)
    {
        $this->repository=$repository;
    }
    public function index(){
        return CView('comments::index');
    }
    public function get_comment_product($id){
        $comment=Comments::with('get_child.getUserInfo','getUserInfo')->where(['product_id'=>$id,'status'=>1,'parent_id'=>0])->orderBy('id','DESC')->paginate(10);
        return $comment;
    }
    public function get_comments(){
        $comment=Comments::with('product','get_parent')->orderBy('id','DESC')->paginate(10);
        return $comment;
    }
    public function add_comment(Request $request){
        if ($request->has('request-type') && $request->get('request-type')=='axios') {
            $product=Product::findOrFail($request->product_id);
            if ($product){
                $comment=new Comments($request->all());
                $comment->saveOrFail();
            }
        }
    }
    public function set_status(Request $request){
        if ($request->has('request-type') && $request->get('request-type')=='axios') {
            $comment=Comments::findOrFail($request->comment_id);
            if ($comment){
                $comment->status=$request->status;
                if ($request->status==1){
                    $message='نظر مورد نظر شما تایید شد.';
                }elseif ($request->status==0){
                    $message='نظر مورد نظر شما رد شد.';
                }
                $comment->update();
                return [
                    'message'=>$message
                ];
            }
        }
    }
}
