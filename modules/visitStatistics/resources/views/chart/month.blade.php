@if(\Illuminate\Support\Facades\Auth::user()->role_id==0)
    <?php
    $args = ['title' => 'آمار بازدید فروشگاه']
    ?>
    <x-panel-box :args="$args">
        <month-visit></month-visit>
    </x-panel-box>
@endif
