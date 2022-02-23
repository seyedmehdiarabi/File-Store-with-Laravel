<?php
$option=['url' => 'userRole'];
$data=isset($userRole) ? $userRole : [];
$form=new \App\Lib\FormBuilder($option, $type,$data);
?>

<?php $form->textInput('name','نام نقش کاربری',['class'=>'form-control']); ?>

<?php $form->btn( $type=='create' ? 'ثبت' : 'ویرایش', $type); ?>

<?php $form->close(); ?>
