<?php
  $status=\Modules\products\Models\Product::ProductStatus();
  $url=isset($url) ? $url : 'admin/products';
  $hide_fields=isset($filters) ? $filters  : [];
?>

<?php
$option=['url' =>$url];
$data=isset($product) ? $product : [];
$form=new \App\Lib\FormBuilder($option, $type,$data);
?>

<?php $form->textInput('title','عنوان محصول',['validate'=>'required','class'=>'total-width']); ?>

<?php $form->editor('text',[]); ?>


<div class="row">

    <div class="col-md-6">

        <?php $form->textInput('price','قیمت محصول',['class'=>'total-width']); ?>
        <?php $form->textInput('download_number','تعداد قسمت‌های محصول',['class'=>'total-width']); ?>
        <?php $form->textInput('download_times','مدت زمان دوره',['class'=>'total-width']); ?>
        <?php $form->textInput('download_size','حجم فایل‌های محصول',['class'=>'total-width']); ?>

        <?php $form->fieldLocation('product_form_right_box') ?>

        @if(array_search('status',$hide_fields)===false)
            <?php $form->select($status,'status','وضعیت محصول',['class'=>'total-width','validate'=>'required'],0); ?>
        @endif

    </div>

    <div class="col-md-6">

        <div class="choice_pic_box" style="padding:20px 10px 0px 20px">
            <?php $url='' ?>
            @if(isset($product))
                @php $url=url('files/products/'. $product->pic) @endphp
            @endif
            <?php $form->imageInput('pic','تصویر محصول',['class'=>'total-width'],$url); ?>
        </div>


        <?php $form->fieldLocation('product_form_left_box') ?>

    </div>

</div>

<p class="message_text">لینک‌ها با استفاده از (،) از هم جدا شود</p>
<?php $form->textarea('links','لینک‌های محصول',['class'=>'total-width','validate'=>'required']); ?>

<p class="message_text">برچسب‌ها با استفاده از (،) از هم جدا شود</p>
<div style="margin-bottom: 20px">
    <?php $form->tagBox('tag','برچسب ها',['class' => 'total-width']) ?>
</div>


<?php $form->btn( $type=='create' ? 'ثبت محصول' : 'ویرایش محصول', $type); ?>

<?php $form->close(); ?>
