<?php
  $args=['title'=>'جست و جو'];
?>


<x-panel-box :args="$args">

    <?php
       $option=['url'=>'admin/category','method'=>'get','class'=>'search_form'];
       $form=new \App\Lib\FormBuilder($option,'create',[])
    ?>

    <?php

         $form->textInput('string','نام دسته',[],$req->get('string',''));

         $form->btn('جست و جو','edit');

         $form->close();

    ?>

</x-panel-box>
