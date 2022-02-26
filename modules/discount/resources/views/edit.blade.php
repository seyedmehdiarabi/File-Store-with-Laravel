@extends('backend-theme::layout')

@section('content')
    <div>
            @include('backend-theme::breadcrumb',['data'=>[
         ['title'=>'مدیریت کد های تخفیف','url'=>url('admin/discount')],
         ['title'=>'ویرایش کد تخفیف','url'=>url('admin/discount/'.$discount->id.'edit')]
    ]])
        @php
            use App\Lib\Jdf;$jdf=new Jdf();
            $discount->expiry_time=$jdf->tr_num($jdf->jdate('Y/n/j',$discount->expiry_time));
        @endphp
        <?php
        $args['title']=' ویرایش کد تخفیف';
        ?>
        <x-panel-box :args="$args">
            @include('discount::_form',['type'=>'edit'])
        </x-panel-box>
    </div>
@endsection
