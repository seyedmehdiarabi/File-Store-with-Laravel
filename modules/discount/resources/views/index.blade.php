@extends('backend-theme::layout')
@section('content')
    <div>
        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت کد های تخفیف','url'=>url('admin/discount')]]])
        @include('discount::_search_form')
        <?php
        $args = [];
        $args['title'] = ' مدیریت کد های تخفیف';
        $args['route'] = 'admin/discount';
        $args['trashCount'] = $trash_discount_count;
        $args['routeParam'] = 'کد تخفیف';
        ?>
        <x-panel-box :args="$args">
            <form method="get" class="search_form">
                @if(isset($_GET['trashed']) && $_GET['trashed']==true)
                    <input type="hidden" name="trashed" value="true">
                @endif
            </form>
            <?php
            \App\Lib\GridView::showTable([
                'dataProvider' => $discount,
                'columns' => [
                    ['label' => 'کد تخفیف', 'attr' => 'name'],
                    ['label' => 'میزان تخفیف', 'attr' => function ($value) {
                        return e(replace_number($value->value)) . ' درصد';
                    }],
                ],
                'route_param' => 'admin/discount',
                'tableLabel' => 'کد تخفیف'
            ]);
            ?>
        </x-panel-box>
    </div>
@endsection
