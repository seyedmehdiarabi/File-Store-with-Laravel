<template>
    <div>
        <v-card
            :disabled="loading"
            :loading="loading"
            id="auth-box"
        >
            <v-row v-if="serverError">
                <v-alert type="error">{{ serverError }}</v-alert>
            </v-row>

            <v-row v-if="validateErrors.length>0">
                <v-alert type="warning">
                    <ul>
                        <li v-for="error in validateErrors" style="list-style: none">{{ error }}</li>
                    </ul>
                </v-alert>
            </v-row>

            <div >
                <v-row>
                    <v-alert type="success" style="width: 100%">
                        <span style="font-size: 15px">کد تایید به شماره موبایل {{ replaceNumber(mobile) }} ارسال شد.</span>
                        <div class="edit-number-register">
                            <v-btn @click="showRegisterHome()" style="margin-right: 10px">ویرایش شماره</v-btn>
                        </div>
                    </v-alert>
                </v-row>

                <v-row :key="'active-code'+render">

                    <v-text-field
                        outlined
                        class="code-field"
                        v-for="index in 6"
                        v-model="code[index]"
                        :key="index"
                        :autofocus="autofocus[index]"
                        @input="send_active_code(index,'/register/active_account',$siteUrl,true)"
                    >
                    </v-text-field>

                </v-row>

                <v-row>

                    <div id="resend_active_code" style="display: flex;">
                        <span @click="resend_active_code()" style="cursor:pointer;font-weight:bold;">ارسال مجدد کد</span>

                        <div class="active-code-counter" style="padding-right:10px" v-if="show_second>0">
                            <span>{{ h }}</span>:<span>{{ m }}</span>:<span>{{ s }}</span>
                        </div>
                    </div>

                </v-row>

            </div>

        </v-card>
    </div>
</template>

<script>
import methods from "../methods";

export default {
    name: "AuthRegisterBox",
    mixins: [methods],
    props:['mobile'],
    data() {
        return {
            serverError: false,
            loading: false,
            valid: false,
            mobileRules: {
                required: v => !!v || 'لطفا شماره موبایل خود را وارد نمایید',
            },
            password: '',
            passwordRules: [
                v => !!v || 'لطفا کلمه عبور خود را وارد نمایید',
            ],
            step: 1,
            activeCode: '',
            validateErrors: [],
            code: [],
            h: '',
            m: '',
            s: '',
            show_second: 0,
            timeOut: null,
            autofocus: {
                1: false,
                2: false,
                3: false,
                4: false,
                5: false,
                6: true
            },
            render: 0
        }
    },
}
</script>

<style>
*{
    font-family: IRANSansWeb!important;
}
</style>
