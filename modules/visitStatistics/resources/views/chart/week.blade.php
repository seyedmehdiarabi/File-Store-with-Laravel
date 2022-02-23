@if(\Illuminate\Support\Facades\Auth::user()->role_id==0)
    <?php
    $args = ['title' => 'آمار بازدید در ساعات مختلف هفته']
    ?>
    <x-panel-box :args="$args">
        <week-visit></week-visit>
    </x-panel-box>
@endif
