<template>
    <v-dialog
      v-model="dialog"
      width="450"
    >

        <v-card :loading="loading" :disabled="loading">

            <v-card-title style="font-size: 16px">
                <v-btn
                   icon
                   @click="dialog=false"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                شماره شناسنامه
            </v-card-title>

            <v-card-text style="padding-top: 20px">

                  <v-form ref="form" v-model="valid">

                      <v-alert v-if="error" type="error">
                          {{ error }}
                      </v-alert>


                      <v-text-field
                         outlined
                         label="شماره شناسنامه"
                         v-model="idNumber"
                         :rules="idNumberNameRules"
                      ></v-text-field>

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
    name: "UserEditIdNumber",
    data(){
        return {
            idNumber:'',
            dialog:false,
            error:false,
            loading:false,
            valid:false,
            idNumberNameRules: [
                v => !!v || 'شماره شناسنامه نمی‌تواند خالی باشد',
            ]
        }
    },
    mounted() {
        this.$root.$on('edit_user_id_number',(value)=>{
             if(value!=='-'){
                 this.idNumber=value;
             }
             this.dialog=true;
        });
    },
    methods:{
        updateName:function (){
            this.$refs.form.validate();
            if(this.valid){
                this.loading=true;
                this.error=false;
                const formData=new FormData();
                formData.append('id_number',this.idNumber);
                const url=this.$siteUrl+'/idNumber/update';
                this.axios.post(url,formData).then(response=>{
                    this.loading=false;
                    if(response.data==='ok'){
                        this.dialog=false;
                        this.$root.$emit('send_get_request',window.location.href);
                    }
                    else {
                        this.error='خطا در ارسال اطلاعات،مجددا تلاش نمایید';
                    }
                }).catch(error=>{
                    this.loading=false;
                    this.error='خطا در ارسال اطلاعات،مجددا تلاش نمایید';
                });
            }
        }
    }
}
</script>
