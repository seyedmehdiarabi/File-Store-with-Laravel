@extends('backend-theme::layout')

@section('content')

    <div>
        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت نقش‌های کاربری','url'=>url('userRole')]]])

        <?php
        $args = ['title' => 'مدیریت نقش های کاربری'];
        $args['route'] = 'userRole';
        $args['trashCount'] = $trash_role_count;
        $args['routeParam'] = 'نقش کاربری';
        ?>

        <x-panel-box :args="$args">
            <?php
            \App\Lib\GridView::showTable([
                'dataProvider' => $userRole,
                'columns' => [
                    ['label' => 'نام نقش', 'attr' => 'name'],
                    ['label' => 'تعداد کاربر', 'attr' => function ($model) {
                        if ($model->userrole_count) {
                            return replace_number($model->userrole_count);
                        } else {
                            return replace_number(0);
                        }
                    }],
                ],
                'actions' => [
                    function ($model) {
                        $url = url('userRole/access/' . $model->id);
                        return '<a href="' . $url . '" style="color:black">
                                  <span  data-toggle="tooltip" data-placement="bottom"  title="دسترسی ها"  class="fa fa-lock"></span>
                                </a> ';
                    }
                ],
                'tableLabel' => 'نقش کاربری',
                'route_param' => 'userRole'
            ]);
            ?>

            {{ $userRole->links() }}
        </x-panel-box>

    </div>

@endsection
