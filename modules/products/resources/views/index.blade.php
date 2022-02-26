@extends('backend-theme::layout')

@section('content')

    <div>
        <?php  $status=\Modules\products\Models\Product::ProductStatus(); define('status',$status); ?>

        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت محصولات','url'=>url('admin/products')]]])

        @includeIf('products::_search_form')

        <?php
            $args=['title'=>'مدیریت محصولات'];
            $args['route']='admin/products';
            $args['trashCount']=$trash_product_count;
            $args['routeParam']='محصول';
        ?>


        <x-panel-box :args="$args">


            <?php
            \App\Lib\GridView::showTable([
                'dataProvider'=>$product,
                'columns'=>[
                    ['label'=>'تصویر محصول','attr'=>function($model){
                        $src=url('files/products/'. $model->pic);
                        return '<img src="'.$src.'" class="product_pic"  style="margin:20px;width:100px">';
                    },'html'=>true],
                    ['label'=>'عنوان','attr'=>'title'],
                    ['label'=>'وضعیت محصول','attr'=>function($model){
                        if (array_key_exists($model->status, status)) {
                            $type = ($model->status == 1) ? "success" : 'info';
                            return '<v-alert type="'.$type.'" style="margin:0;" border="left" text>'. status[$model->status] .'</v-alert>';
                        }
                    },'html'=>true]
                ],
                'route_param'=>'admin/products',
                'tableLabel'=>'محصول'
            ]);
            ?>

            {{ $product->links() }}

        </x-panel-box>

    </div>

@endsection
