<?php
$args = [];
$args['title'] = 'جستجو';
?>
<x-panel-box :args="$args">
    <?php
    $option = ['url' => 'admin/discount', 'method' => 'get', 'class' => 'search_form'];
    $form = new \App\Lib\FormBuilder($option, 'create', []);
    ?>

    <?php
    $form->textInput('string', 'کد تخفیف', [], $req->get('string', ''));
    $form->btn('جست و جو', 'edit');
    $form->close();
    ?>

</x-panel-box>
