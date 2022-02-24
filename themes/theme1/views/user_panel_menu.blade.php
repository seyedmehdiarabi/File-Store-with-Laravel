<v-card elevation="1" class="profile_menu">
    <v-card-title>حساب کاربری شما</v-card-title>
    <v-card-text>
        <?php $active='' ?>
        <?php
        $sideBarMenu=[];
        $sideBarMenu[0]=['label'=>'پروفایل','icon'=>'mdi-account','url'=>url('user/profile')];
        $sideBarMenu=CompleteData('user_panel_menu',$sideBarMenu);
        ?>
        <ul class="profile_menu_ul">
            @foreach($sideBarMenu as $menu)
                <li>
                    <a href="{{ $menu['url'] }}" class="router-link">
                        <v-icon>{{ $menu['icon'] }}</v-icon>
                        {{ $menu['label'] }}
                    </a>
                    @if(array_key_exists('count',$menu))
                        @if(isset(${$menu['count']}) && ${$menu['count']}>0)
                            <span class="count_span">{{ replace_number(${$menu['count']}) }}</span>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    </v-card-text>
</v-card>
