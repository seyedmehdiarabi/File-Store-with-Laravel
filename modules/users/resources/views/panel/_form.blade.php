<?php
$option=['url' => 'users'];
$data=isset($user) ? $user : [];
$form=new \App\Lib\FormBuilder($option, $type,$data);
?>

<?php $form->textInput('name','نام و نام خانوادگی',['validate'=>'required']); ?>

<?php $form->textInput('username','نام کاربری (برای نقش مدیر)',[]); ?>

<?php $form->textInput('mobile','شماره موبایل ',['validate'=>'required']); ?>

<?php $form->textInput('password','کلمه عبور',['type'=>'password','validate'=>$type==='create' ? 'required' : '']); ?>

<?php
$account_status=['active'=>'فعال','InActive'=>'غیر فعال'];
?>
<?php $form->select($account_status,'account_status','وضعیت اکانت',[]); ?>

<?php $form->select($roles,'role','نقش کاربری  ',[]); ?>

<?php $form->btn( $type=='create' ? 'ثبت کاربر' : 'ویرایش اطلاعات کاربر', $type); ?>

<?php $form->close(); ?>
