<template>
    <v-card
        :disabled="disabled"
        :loading="loading"
        id="auth-box"
    >
        <slot name="before-login-form"/>

        <v-row v-if="serverError">
            <v-alert type="error">{{ serverError }}</v-alert>
        </v-row>

        <v-form v-model="valid" ref="form">

            <v-row v-if="this.type!=='admin-from'">

                <v-text-field
                    label="شماره موبایل"
                    outlined
                    v-model="mobile"
                    :rules="[mobileRules.required,checkMobileNumber]"
                ></v-text-field>

            </v-row>

            <v-row v-else>

                <v-text-field
                    label="نام کاربری"
                    outlined
                    v-model="username"
                    :rules="usernameRules"
                ></v-text-field>

            </v-row>

            <v-row>

                <v-text-field
                    label="کلمه عبور"
                    outlined
                    v-model="password"
                    :rules="passwordRules"
                    type="password"
                ></v-text-field>

            </v-row>

            <v-row>
                <a class="router-link reset_password_link" :href="$siteUrl+'/password/reset'">بازیابی کلمه عبور</a>
            </v-row>

            <v-row>
                <v-checkbox
                    v-model="remember"
                    label="مرا با خاطر بسپار"
                    color="#ef394e"
                    class="remember-check-box"
                ></v-checkbox>
            </v-row>

            <div class="send_btn" @click="login()">
                <span v-if="this.type!=='admin-from'">ورود به فروشگاه</span>
                <span v-else>ورود به پنل مدیریت</span>
            </div>
        </v-form>

        <v-alert class="alert-register" v-if="this.type!=='admin-from'">
            <span>کاربر جدید هستید ؟</span>
            <span>
                 <a class="data_link" :href="$siteUrl+'/register'">ثبت نام در سایت</a>
            </span>
        </v-alert>

    </v-card>
</template>

<script>
import methods from "../methods";

export default {
    name: "AuthLoginBox",
    mixins: [methods],
    data() {
        return {
            loading: false,
            disabled: false,
            valid: false,
            mobile: '',
            mobileRules: {
                required: v => !!v || 'لطفا شماره موبایل خود را وارد نمایید',
            },
            password: '',
            passwordRules: [
                v => !!v || 'لطفا کلمه عبور خود را وارد نمایید',
            ],
            remember: false,
            serverError: false,
            usernameRules: [
                v => !!v || 'لطفا نام کاربری خود را وارد نمایید',
            ],
            username: ''
        }
    },
    props: ['type']
}
</script>

<style scoped>
@import "../../assets/auth.css";
</style>
