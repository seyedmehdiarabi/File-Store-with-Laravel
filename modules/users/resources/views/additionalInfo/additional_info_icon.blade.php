<v-tooltip bottom>
    <template v-slot:activator="{on,attrs}">
        <a class="router-link" href="{{ $url }}" style="padding-left: 5px">
            <v-icon
                color="black"
                v-bind="attrs"
                v-on="on"
            >
                mdi-information
            </v-icon>
        </a>
    </template>
    <span>اطلاعات تکمیلی کاربر</span>
</v-tooltip>
