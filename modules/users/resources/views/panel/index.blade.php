@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت کاربران','url'=>url('users')]]])

        @include('users::panel._search_form')
        <?php
        $args=['title'=>'مدیریت کاربران'];
        $args['route']='users';
        $args['trashCount']=$trash_user_count;
        $args['routeParam']='کاربر';
        ?>
        <x-panel-box :args="$args">
            <?php
            \App\Lib\GridView::showTable([
                'dataProvider'=>$users,
                'columns'=>[
                    ['label'=>'نام کاربر','attr'=>function($model){
                        if (!empty($model->name)){
                            return  e($model->name);
                        }
                        else{
                            return 'ثبت نشده' ;
                        }
                    }],
                    ['label'=>'شماره موبایل','attr'=>function($model){
                        return e(replace_number($model->mobile));
                    }],
                    ['label'=>'تاریخ عضویت','attr'=>function($model){
                        $Jdf=new \App\Lib\Jdf();
                        $e=explode(' ',$model->created_at);
                        $e2=explode('-',$e[0]);
                        return e(replace_number($Jdf->gregorian_to_jalali($e2[0],$e2[1],$e2[2],'-')) );
                    }],
                    ['label'=>'وضعیت','attr'=>function($model){
                        if ($model['account_status']=='active'){
                            return  '<span class="alert alert-success">فعال</span>';
                        }
                        else{
                            return '<span class="alert alert-danger">غیر فعال</span>';
                        }
                    },'html'=>true],
                    ['label'=>'نقش کاربری','attr'=>function($model){
                        if ($model->getRole){
                            return e($model->getRole->name);
                        }
                        elseif($model->role=='admin'){
                            return  'مدیر';
                        }
                        else{
                            return  ' کاربر عادی';
                        }
                    }]
                ],
                'route_param'=>'users',
                'tableLabel'=>'کاربر',
                'tableCssClass'=>'user_table',
                'actions'=>[
                    function($model){
                        $url=url('users/'.$model->id);
                        return '<a href="'.$url.'" class="router-link"><v-icon>mdi-eye</v-icon></a> ';
                    }
                ],
            ]);
            ?>
            {{ $users->links() }}
        </x-panel-box>

    </div>

@endsection
