<?php
$view=run_action($name,[],true);
?>

@if(sizeof($view)>0)
    @include($view[0])
@else

    @if(isset($component) && $component=='yes')
        <?php echo '<'.$path.'  :item="slotProps.item"></'.$path.'>'; ?>
    @else
        @includeIf($path)
    @endif

@endif
