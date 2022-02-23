<template>
    <div class="auth-menu-box">
        <v-menu offset-y min-width="230">
            <template v-slot:activator="{on,attrs}">
                  <v-btn
                    v-if="login==='yes'"
                    v-bind="attrs"
                    v-on="on"
                    text
                  >

                      <v-icon size="30" right>
                          mdi-account-outline
                      </v-icon>

                      <v-icon right>
                          mdi-chevron-down
                      </v-icon>

                  </v-btn>

                <v-btn
                    v-else
                    v-bind="attrs"
                    v-on="on"
                    text
                    class="login-btn"
                    >
                    <v-icon size="30" right>
                        mdi-account-outline
                    </v-icon>
                    <span style="padding-right: 10px">ورود به حساب کاربری</span>
                </v-btn>
            </template>
            <v-list class="auth-menu">

                <v-list-item v-if="role==='admin' || role_id>0">
                    <v-list-item-icon>
                        <v-icon>mdi-tablet-dashboard</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>
                        <a :href="$siteUrl+'/admin'" style="color: black">پنل مدیریت</a>
                    </v-list-item-title>
                </v-list-item>

                <v-list-item v-if="login==='no'">
                    <v-list-item-title>
                        <a :href="$siteUrl+'/login'">
                            <v-btn  class="login-link" color="primary">
                                ورود به {{ shop_name}}
                            </v-btn>
                        </a>
                    </v-list-item-title>
                </v-list-item>

                <v-list-item v-if="login==='no'" style="margin-top: 10px">
                    <v-list-item-title style="text-align: center">
                        <span>کاربر جدید هستید؟</span>
                        <a :href="$siteUrl+'/register'" class="register-link">
                            ثبت نام
                        </a>
                    </v-list-item-title>
                </v-list-item>

                <v-list-item >
                    <v-list-item-icon>
                        <v-icon>mdi-account-details-outline</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title @click="$root.$emit('send_get_request',$siteUrl+'/user/profile')">
                        پروفایل
                    </v-list-item-title>
                </v-list-item>

                <slot></slot>

                <v-divider v-if="login==='yes'"></v-divider>
                <v-list-item v-if="login==='yes'">
                    <v-list-item-icon @click="$root.$emit('send_post_request',$siteUrl+'/logout',{})">
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title @click="$root.$emit('send_post_request',$siteUrl+'/logout',{})">
                        خروج از حساب کاربری
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
export default {
    name: "AuthMenu",
    props:['login','role_id','role','shop_name']
}
</script>

<style>
@import "../../css/style.css";
</style>
