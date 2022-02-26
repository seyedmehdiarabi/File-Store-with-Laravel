<?php
$option=['url' => 'admin/category','files'=>true];
$data=isset($category) ? $category : [];
$form=new \App\Lib\FormBuilder($option, $type,$data);
?>

<?php $form->textInput('name','نام دسته',['validate'=>'required']); ?>

<?php $form->textInput('ename','نام انگلیسی دسته ',['validate'=>'required']); ?>

<?php $form->select($catList,'parent_id','انتخاب سردسته',['validate'=>'required']); ?>

<v-label>معرفی :</v-label>
<?php $form->editor('introduction', ['form-control']); ?>

<v-label>توضیحات :</v-label>
<?php $form->editor('description', ['form-control']); ?>

<?php $form->fileInput('pic','تصویر دسته',null,'image/*'); ?>

@if($type=='edit')
    <div class="form-group img-box-panel">
        <img @if(!empty($category->img)) src="{{ url('files/upload/'.$category->img) }}" @endif>
    </div>
@endif

<?php $form->btn( $type=='create' ? 'ثبت دسته' : 'ویرایش دسته', $type); ?>

<?php $form->close() ?>
