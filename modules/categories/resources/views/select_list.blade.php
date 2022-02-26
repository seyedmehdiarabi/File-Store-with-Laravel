<?php
if(isset($catList)){
    $form->select($catList,'cat_id','انتخاب دسته',['class'=>'total-width','validate'=>'required','dense'=>true]);
}
?>
