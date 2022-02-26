<template>
    <v-card>
        <v-list shaped>
            <div class="pa-4 d-flex align-center border-bottom-01">
                <img class="w-20 h-20 ml-5 rounded-circle border_img bg-gray-300"
                     :src="user && user['pic_profile'] ? $siteUrl+'/files/upload/'+user['pic_profile'] : '/files/icons/default_user.png'"
                     alt="عکس نمایه شخصی">
                <div>
                    <span class="bold">{{ user['name'] }}</span>
                    <span class="d-block mt-1 font-size-12">
                        <template v-if="user['role']==='admin'">
                            مدیر سایت
                        </template>
                        <template v-else-if="user['role']==='user'">
                            کاربر
                        </template>
                    </span>
                </div>
            </div>
            <v-list-item-group
                v-model="selectedItem"
                color="primary"
                class="mt-4"
            >
                <v-list-item
                    v-for="(item, i) in items"
                    :key="i"
                >
                    <a class="d-flex router-link" :href="$siteUrl+item.url">
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.text"></v-list-item-title>
                        </v-list-item-content>
                    </a>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-card>
</template>

<script>
export default {
    props: ['user'],
    name: "ListItemProfile",
    data: () => ({
        selectedItem: null,
        items: [
            {text: 'اطلاعات حساب', icon: 'mdi-account', url: '/profile'},
            {text: 'سفارشات', icon: 'mdi-shopping-outline', url: '/profile/order'},
        ],
    }),
    mounted() {
        this.setDefaultActiveItem();
    },
    methods: {
        setDefaultActiveItem: function () {
            const keys = Object.keys(this.items);
            const pageUrl = window.location.href;
            for (let i = 0; i < keys.length; i++) {
                if (this.items[keys[i]].url !== undefined) {
                    if (this.$siteUrl + this.items[keys[i]].url === pageUrl) {
                        this.selectedItem = eval(keys[i]);
                    }
                }
            }
        }
    }
}
</script>

<style>
.search-box {
    padding: 15px 15px 0 15px;
}
.v-application .green--text .v-list-item--active {
    background: #4caf5038;
}

.panel-menu .v-list-item a {
    color: inherit;
}

.v-navigation-drawer__content {
    overflow-y: hidden !important;
}
</style>
