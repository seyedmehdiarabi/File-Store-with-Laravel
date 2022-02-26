<?php
$args = [];
$args['title'] = 'جستجو';
?>
<x-panel-box :args="$args">
    <?php
    $option=['url' => 'admin/orders','method'=>'get','class'=>'search_form'];
    $form=new \App\Lib\FormBuilder($option, 'create',[]);
    ?>

    <?php
    $form->textInput('order_id','شماره سفارش',[],$req->get('order_id',''));

    $form->dateInput('first_date','شروع از تاریخ',
        [],
        e($req->get('first_date',''))
    );

    $form->dateInput('end_date','تا تاریخ',
        [],
        e($req->get('end_date',''))
    );

    $form->btn( 'جستجو', 'edit');

    $form->close();
    ?>

</x-panel-box>
