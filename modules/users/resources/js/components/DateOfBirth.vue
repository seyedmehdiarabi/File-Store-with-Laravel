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
                انتخاب تاریخ تولد
            </v-card-title>

            <v-card-text style="padding-top:20px">

                <v-alert type="error" v-if="serverError">
                    {{ serverError }}
                </v-alert>

                {{ dateFormatted }}
                <v-date-picker
                    v-model="date"
                    locale="fa"
                    width="100%"
                    color="green lighten-1"
                >
                </v-date-picker>


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
import methods from '../methods';
export default {
    name: "DateOfBirth",
    mixins:[methods],
    data(){
        return {
            dialog:false,
            valid:false,
            loading:false,
            serverError:false,
            date:'',
            dateFormatted:''
        }
    },
    mounted() {
        this.$root.$on('edit_user_date_of_birth',(value)=>{
            if(value!=='-'){
                this.dateFormatted=value;
                this.date=this.parseDate(this.dateFormatted);
            }
            this.dialog=true;
        });
    },
    methods:{
        sendRequest:function () {
            if(this.dateFormatted!==''){
                this.loading=true;
                this.serverError=false;
                const formData=new FormData();
                formData.append('date_of_birth',this.dateFormatted);
                const url=this.$siteUrl+'/date-of-birth/update';
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
    },
    watch:{
        date(val){
            this.dateFormatted=this.formatData(this.date);
        }
    }
}
</script>


