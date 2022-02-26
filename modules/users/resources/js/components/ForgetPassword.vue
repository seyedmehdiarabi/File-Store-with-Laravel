<template>
    <v-card
        id="auth-box"
        :loading="loading"
        :disabled="loading"
    >
        <slot name="before-forget-form"></slot>
        <v-row v-if="error">
            <p style="color: red">{{ error }}</p>
        </v-row>

        <div v-if="step===1">
            <v-form v-model="valid" ref="form">
                <v-row>
                    <v-text-field
                        label="شماره موبایل"
                        outlined
                        v-model="mobile"
                        :rules="[mobileRules.required,checkMobileNumber]"
                    ></v-text-field>
                </v-row>
                <v-btn
                    class="mb-4 mr-4"
                    color="success"
                    dark
                    @click="forgetPassword"
                >
                    بازیابی کلمه عبور
                </v-btn>
            </v-form>
        </div>

        <div v-else-if="step===2">
            <v-row>
                <v-alert type="success" style="width: 100%">
                    <span style="font-size: 15px">کد تاییدارسال شده به شماره موبایل {{ replaceNumber(mobile) }} وارد کنید.</span>
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
                    @input="send_forget_code(index,'/password/confirm')"
                >
                </v-text-field>

            </v-row>

            <v-row>

                <div id="resend_active_code" style="display: flex;">
                    <span @click="resend_active_code('forget_password')" style="cursor:pointer;font-weight:bold;">ارسال مجدد کد</span>

                    <div class="active-code-counter" style="padding-right:10px" v-if="show_second>0">
                        <span>{{ h }}</span>:<span>{{ m }}</span>:<span>{{ s }}</span>
                    </div>
                </div>

            </v-row>

        </div>

        <div v-else-if="step===3">
            <v-form v-model="valid" ref="form3">

                <v-row>
                    <v-text-field
                        label="کلمه عبور"
                        outlined
                        v-model="password1"
                        :rules="password1Rules"
                    ></v-text-field>
                </v-row>

                <v-row>
                    <v-text-field
                        label="تکرار کلمه عبور"
                        outlined
                        v-model="password2"
                        :rules="[password2Rules.required,confirmationPassword]"
                    ></v-text-field>
                </v-row>
                <v-btn
                    class="mb-4 mr-4"
                    color="success"
                    dark
                    @click="changePassword"
                >
                    تغییر کلمه عبور
                </v-btn>

            </v-form>
        </div>

        <div></div>
    </v-card>
</template>

<script>
import methods from "../methods";

export default {
    name: "ForgetPassword",
    mixins: [methods],
    data() {
        return {
            loading: false,
            step: 1,
            error: '',
            valid: false,
            mobileRules: {
                required: v => !!v || 'لطفا شماره موبایل خود را وارد نمایید',
            },
            mobile: '',
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
            render: 0,
            token: '',
            password1: '',
            password2: '',
            password1Rules: [
                v => !!v || 'لطفا کلمه عبور خود را وارد نمایید',
            ],
            password2Rules: [
                v => !!v || 'لطفا کلمه عبور خود را وارد نمایید',
            ],
        }
    },
    methods: {
        forgetPassword: function () {
            this.$refs.form.validate();
            if (this.valid && !this.loading) {
                this.loading = true;
                this.error = '';
                const formData = new FormData();
                formData.append('mobile', this.mobile);
                const url = this.$siteUrl + '/password/email';
                this.axios.post(url, formData).then(response => {
                    this.loading = false;
                    if (response.data['status'] === 'ok') {
                        this.step = 2;
                        this.show_second = 180;
                        if (this.timeOut !== null) {
                            clearInterval(this.timeOut);
                        }
                        this.timeOut = setInterval(this.counter, 1000);
                    } else {
                        this.error = response.data['message'];
                    }
                }).catch(error => {
                    this.loading = false;
                    this.error = 'خطا در ارتباط با سرور، مجددا تلاش نمایید!';
                });
            }
        }
    },
    beforeDestroy() {
        if(this.timeOut!==null){
            clearInterval(this.timeOut);
        }
    }
}
</script>

<style>
 *{
     font-family: IRANSansWeb !important;
 }
</style>
