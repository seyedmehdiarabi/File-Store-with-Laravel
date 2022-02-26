<?php
$option = ['url' => 'admin/discount'];
$data = $discount ?? [];
$form = new \App\Lib\FormBuilder($option, $type, $data);
?>

<?php $form->textInput('name', 'نام کد تخفیف ', ['class' => 'form-control left']); ?>

<?php $form->textInput('value', 'میزان تخفیف', ['class' => 'form-control left','suffix'=>'%']); ?>

<?php $form->btn($type == 'create' ? 'ثبت کد تخفیف' : 'ویرایش کد تخفیف', $type); ?>

<?php $form->close(); ?>
