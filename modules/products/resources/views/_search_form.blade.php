<?php
   $args=[];
   $args['title']='جست و جو';
?>

<x-panel-box :args="$args">

    <?php

        $option=['url' => 'admin/products','method'=>'get','class'=>'search_form'];

        $form=new \App\Lib\FormBuilder($option, 'create',[]);

        $orderByList=CompleteData('product_list_ordering',['new'=>'جدید ترین محصولات']);

    ?>

    <?php

        $form->textInput('string','عنوان محصول',[],$req->get('string',''));

        $form->select($orderByList,'order_by','مرتب سازی بر اساس',['dense'=>true],$req->get('order_by','new'));

        $form->select($status,'status','وضعیت محصول',['dense'=>true],$req->get('status'));

        $form->btn('جست و جو', 'edit');

        $form->close();

    ?>


</x-panel-box>
