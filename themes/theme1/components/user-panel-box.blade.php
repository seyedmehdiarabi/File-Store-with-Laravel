<v-card elevation="1" class="user-panel-box">

    @if(array_key_exists('title',$args) || isset($header))
        <v-card-title class="user-panel-box-header">
            @if(array_key_exists('title',$args))
                {{ $args['title'] }}
            @else
                @if(isset($header))
                    {{ $header }}
                @endif
            @endif
        </v-card-title>
    @endif


    <v-card-text>
        {{ $slot }}
    </v-card-text>

</v-card>
