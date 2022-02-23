<?php
$args=['title'=>'جستجو'];
?>

<x-panel-box :args="$args">

    <?php

    $option=['url' => 'users','method'=>'get','class'=>'search_form'];
    $form=new \App\Lib\FormBuilder($option, 'create',[]);

    $form->textInput('name','نام کاربر',[],$req->get('name',''));

    $form->textInput('mobile','شماره موبایل',[],$req->get('mobile',''));

    $form->select($roles,'role','نقش کاربری',[],$req->get('role',''));

    $form->btn( 'جستجو', 'edit');

    $form->close();

    ?>
</x-panel-box>
