@if(isset($name))
    <?php $position_views = position_view($name); ?>
    @foreach ($position_views as $view)
        <?php $data = isset($args) ? $args : [] ?>
        @includeIf($view['view'],$data)
    @endforeach
@endif
