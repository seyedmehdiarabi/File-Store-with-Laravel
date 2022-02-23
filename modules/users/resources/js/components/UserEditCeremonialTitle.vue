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
                عنوان تشریفاتی
            </v-card-title>

            <v-card-text style="padding-top: 20px">

                <v-form ref="form" v-model="valid">

                    <v-alert v-if="error" type="error">
                        {{ error }}
                    </v-alert>

                    <v-combobox
                        label="عنوان تشریفاتی"
                        outlined
                        dense
                        return-object
                        v-model="ceremonial_select"
                        :items="ceremonial"
                        item-text="name"
                        item-value="name"
                        @change="change"
                        :rules="ceremonialRules"
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
    name: "CeremonialTitle",
    data() {
        return {
            ceremonial_select: null,
            ceremonial: [
                'جناب آقای دکتر',
                'سرکار خانم دکتر',
                'جناب آقای مهندس',
                'سرکار خانم مهندس',
                'جناب آقای',
                'سرکار خانم'
            ],
            ceremonialRules: [
                v => !!v || 'لطفا عنوان تشریفاتی را انتخاب نمایید',
            ],
            dialog: false,
            error: false,
            loading: false,
            valid: false,
        }
    },
    mounted() {
        this.$root.$on('edit_user_ceremonial_title', (value) => {
            if (value !== '-') {
                this.ceremonial_select = value;
            }
            this.dialog = true;
        });
    },
    methods: {
        change: function (value) {
            this.ceremonial_select = value;
        },
        updateName: function () {
            this.$refs.form.validate();
            if (this.valid) {
                this.loading = true;
                this.error = false;
                const formData = new FormData();
                formData.append('ceremonial_title', this.ceremonial_select);
                const url = this.$siteUrl + '/ceremonial_title/update';
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
