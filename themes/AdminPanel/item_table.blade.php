<?php
$create_param='';
$trash_param='';
if(isset($queryString) && is_array($queryString))
{
    $create_param='?'.$queryString['param'].'='.$queryString['value'];
    $trash_param='&'.$queryString['param'].'='.$queryString['value'];
}
?>

<panel-menu-box>
    <template slot="items">

        @if(!isset($remove_new_record) || $remove_new_record==false)
            <v-list-item>
                <v-list-item-icon><v-icon>mdi-pencil</v-icon></v-list-item-icon>
                <v-list-item-title>
                    <a class="router-link" href="{{ url($route.'/create').$create_param }}">
                        <span class="fa fa-pencil"></span>
                        <span>افزودن {{ $title }} جدید</span>
                    </a>
                </v-list-item-title>
            </v-list-item>
        @endif

        <v-list-item>
            <v-list-item-icon><v-icon>mdi-delete</v-icon></v-list-item-icon>
            <v-list-item-title>
                <a class="router-link" href="{{ url($route.'?trashed=true').$trash_param }}">
                    سطل زباله ({{ replace_number($count) }})
                </a>
            </v-list-item-title>
        </v-list-item>

        <delete-multiple title="{{ $title }}"></delete-multiple>

        <restore-multiple title="{{ $title }}"></restore-multiple>

        @if(isset($other))
            @foreach ($other as $item)
                <v-list-item>
                    <v-list-item-icon><v-icon>{{ $item['icon'] }}</v-icon></v-list-item-icon>
                    <v-list-item-title>
                        <a class="router-link" href="{{ $item['url'] }}">
                            {{ $item['label'] }}
                        </a>
                    </v-list-item-title>
                </v-list-item>
            @endforeach
        @endif
    </template>
</panel-menu-box>
