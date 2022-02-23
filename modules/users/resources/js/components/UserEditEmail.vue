<template>
    <v-dialog
        v-model="dialog"
        width="450"
    >
        <v-card :loading="loading" :disabled="loading">
            <v-card-title style="font-size:16px">
                <v-btn
                    icon
                    @click="dialog = false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
               ثبت ایمیل
            </v-card-title>

            <v-card-text style="padding-top:20px">

                <v-form   ref="form" v-model="valid">

                    <v-alert type="error" v-if="serverError">
                        {{ serverError }}
                    </v-alert>

                    <v-text-field
                        outlined
                        label="ایمیل"
                        v-model="email"
                        :rules="emailRules"
                        dir="ltr"
                    ></v-text-field>

                </v-form>


            </v-card-text>


            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="success"
                    @click="sendRequest()"
                >
                    ثبت
                </v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>
</template>

<script>
    export default {
        name: "UserEditEmail",
        data(){
            return {
                dialog:false,
                valid:false,
                loading:false,
                serverError:false,
                emailRules: [
                    v => !!v || 'لطفا ایمیل را وارد نمایید',
                    v => /.+@.+/.test(v) || 'ایمیل وارد شده معتبر نمی باشد',
                ],
                email:''
            }
        },
        mounted() {
            this.$root.$on('edit_user_email',(value)=>{
                if(value!=='-'){
                    this.email=value;
                }
                this.dialog=true;
            });
        },
        methods:{
            sendRequest:function () {
                this.$refs.form.validate();
                if(this.valid){
                    this.loading=true;
                    this.serverError=false;
                    const formData=new FormData();
                    formData.append('email',this.email);
                    const url=this.$siteUrl+'/email/update';
                    this.axios.post(url,formData).then(response=>{
                        this.loading=false;
                        if(response.data==='ok'){
                            this.dialog=false;
                            this.$root.$emit('send_get_request',window.location.href);
                        }
                        else{
                            this.serverError=response.data;
                        }
                    }).catch(error=>{
                        this.loading=false;
                        this.serverError='خطا در ارسال اطلاعات،مجددا تلاش نمایید';
                    });
                }
            }
        }
    }
</script>

