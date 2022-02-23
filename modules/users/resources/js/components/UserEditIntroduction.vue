<template>

    <v-dialog v-model="dialog" width="450">

        <v-card :loading="loading" :disabled="loading">

            <v-card-title style="font-size: 16px">
                <v-btn
                    icon
                    @click="dialog=false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                نحوه آشنایی
            </v-card-title>

            <v-card-text style="padding-top: 20px">

                <v-form ref="form" v-model="valid">

                    <v-alert v-if="error" type="error">
                        {{ error }}
                    </v-alert>

                    <v-combobox
                        label="نحوه آشنایی"
                        outlined
                        dense
                        return-object
                        v-model="introduction_select"
                        :items="introduction"
                        item-text="name"
                        item-value="name"
                        @change="change"
                        :rules="introductionRules"
                    >

                    </v-combobox>

                </v-form>

            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="success"
                    @click="updateName()"
                >
                    ثبت
                </v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>

</template>

<script>
export default {
    name: "UserEditIntroduction",
    data() {
        return {
            introduction_select: null,
            introduction: [
                'دوستان و آشنایان',
                'تبلیغات',
                'شبکه‌های اجتماعی',
                'جستجو در وب',
                'وب‌سایت شرکت'
            ],
            introductionRules: [
                v => !!v || 'لطفا نحوه آشنایی را انتخاب نمایید',
            ],
            dialog: false,
            error: false,
            loading: false,
            valid: false,
        }
    },
    mounted() {
        this.$root.$on('edit_user_introduction', (value) => {
            if (value !== '-') {
                this.introduction_select = value;
            }
            this.dialog = true;
        });
    },
    methods: {
        change: function (value) {
            this.introduction_select = value;
        },
        updateName: function () {
            this.$refs.form.validate();
            if (this.valid) {
                this.loading = true;
                this.error = false;
                const formData = new FormData();
                formData.append('introduction', this.introduction_select);
                const url = this.$siteUrl + '/introduction/update';
                this.axios.post(url, formData).then(response => {
                    this.loading = false;
                    if (response.data === 'ok') {
                        this.dialog = false;
                        this.$root.$emit('send_get_request', window.location.href);
                    } else {
                        this.error = 'خطا در ارسال اطلاعات،مجددا تلاش نمایید';
                    }
                }).catch(error => {
                    this.loading = false;
                    this.error = 'خطا در ارسال اطلاعات،مجددا تلاش نمایید';
                });
            }
        }
    }
}
</script>
