
<?php
    if(!isset($submission)){
        $submission=$order->submissions;
    }

?>

@php use App\Lib\Jdf;use Modules\orders\Models\Orders;$Jdf=new Jdf(); $OrderStatus=Orders::OrderStatus() @endphp
@foreach($submission as $key=>$value)

    <?php
      $args=[];
      $args['title']='جزییات مرسوله - '.replace_number($value->id);
    ?>

    <x-panel-box :args="$args">

          @includeIf('orders::submission.detail')

          <order-step :steps="{{ json_encode($OrderStatus) }}" :send_status="{{ $value['send_status'] }}" :order_id="{{ $value->id }}"></order-step>

          @includeIf('orders::product-detail')

          @includeIf('orders::submission.submission-events')

    </x-panel-box>
@endforeach


