<template>
<div class="">
    <v-app-bar
        color="white"
        flat
        height="auto"
        >
        <v-container>
            <v-row class="custom-container my-3">
                <ul class="header-ul">
                    <li><a class="pl-4" href="https://t.me/">کانال تلگرام</a></li>
                    <li><a class="pl-4" href="#">درباره ما</a></li>
                    <li><a href="#">همکاری با ما</a></li>
                </ul>
                <ul class="header-ul mr-auto">
                    <li><a class="pl-4" href="#">سوالات متدوال</a></li>
                    <li><a class="" href="#" >ارتباط با ما</a></li>
                </ul>
            </v-row>
            <v-divider></v-divider>
            <v-row class="custom-container my-3">
                <v-toolbar-title class="brand">فروش فايل</v-toolbar-title>
                <div class="container1" style="margin: 0 auto">
                    <input type="text" placeholder="دنبال چی میگردی؟">
                    <div class="search"></div>
                </div>
                <div class="d-flex align-center mr-auto" style="z-index: 2">
                    <v-badge
                        class="mx-10 cursor-pointer"
                        color="primary"
                        :content="count_cart"
                        id="count_cart"
                    >
                        <a class="router-link" v-bind:href="$siteUrl+'/cart'"><v-icon>mdi-cart-outline</v-icon></a>
                    </v-badge>
                    <div @click="drawer = true" class="cursor-pointer" v-if="login">
                        <span>{{ login.name }}</span>
                        <v-avatar>
                            <img
                                :src="login && login.pic_profile ? $siteUrl+'/files/upload/'+login.pic_profile : $siteUrl+'/files/icons/default_user.png'"
                                alt="photo"
                            >
                        </v-avatar>
                    </div>
                    <div class="mr-auto" v-else>
                        <a href="/login-register">
                            <v-btn
                                outlined
                                color="primary"
                            >
                                ورود/ثبت نام
                            </v-btn>
                        </a>
                    </div>
                </div>
            </v-row>
        </v-container>
    </v-app-bar>

    <v-navigation-drawer
        class="accent-4"
        v-model="drawer"
        absolute
        temporary
        dark
        color="#475569"
    >
        <v-list>
            <a target="_blank" v-if="login && login['role']==='admin'" class="d-flex white--text" :href="$siteUrl+'/admin'" style="padding: 0 16px">
                <v-list-item-icon>
                    <v-icon>mdi-gavel</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>پنل مدیریت</v-list-item-title>
                </v-list-item-content>
            </a>
            <v-list-item
                v-for="item in items"
                :key="item.title"
                link
            >
                <a class="d-flex white--text" :href="$siteUrl+item.url">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </a>
            </v-list-item>
            <div class="pa-2">
                <a :href="$siteUrl+'/logout'">
                    <v-btn block>
                        خروج از حساب كاربری
                    </v-btn>
                </a>
            </div>
        </v-list>
    </v-navigation-drawer>
    <div class="bg-gray-600">
        <v-row class="custom-container">
            <ul class="header-ul">
                <li><a class="pl-4 router-link" :href="$siteUrl+'/'">صفحه اصلی</a></li>
                <li><a class="pl-4 router-link" :href="$siteUrl+'/category'">دسته بندی</a></li>
            </ul>
        </v-row>
    </div>

</div>
</template>
<script>
    export default {
        name: "HeaderSearch",
        props:['login'],
        data: () => ({
            drawer: false,
            items: [
                { title: 'پروفایل کاربری', icon: 'mdi-account-box',url:'/profile' },
                { title: 'سفارشات', icon: 'mdi-shopping-outline', url: '/profile/order' },
            ],
            count_cart:0,
        }),
        mounted() {
            this.axios.get(this.$siteUrl+'/get_count_cart').then(response=>{
                this.count_cart = response.data;
            });
        },
    }
</script>

<style scoped>

</style>
