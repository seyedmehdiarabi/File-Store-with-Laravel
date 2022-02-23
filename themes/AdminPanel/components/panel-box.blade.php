<panel-box>
    <div>

        @if(array_key_exists('title',$args))
            <div class="header">
                {{ $args['title'] }}

                @if(array_key_exists('trashCount',$args) && $args['trashCount']!==null)
                    @include('backend-theme::item_table',
                       [
                           'count'=>$args['trashCount'],
                           'route'=>$args['route'],
                           'title'=>$args['routeParam'],
                           'other'=>array_key_exists('items',$args) ? $args['items'] : [],
                           'remove_new_record'=>array_key_exists('remove_new_record',$args) ? true : false,
                           'queryString'=>array_key_exists('queryString',$args) ? $args['queryString'] : null
                       ]
                )
                @endif
            </div>
        @else
            <div class="header">
                {{ $header }}
            </div>
        @endif

        <div class="panel_content">
            {{ $slot }}
        </div>

    </div>
</panel-box>
