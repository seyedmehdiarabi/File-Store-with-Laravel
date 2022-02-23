<template>
    <div>
        <v-dialog
           v-model="dialog"
           width="500"
        >
            <v-card>
                <v-card-text>

                    <div class="response-message">
                        <v-icon size="25" color="green" v-if="type==='success'">mdi-check</v-icon>
                        <v-icon size="25" color="red" v-if="type==='error'">mdi-alert</v-icon>

                        {{ text }}
                    </div>

                    <ul v-if="errors!==undefined && errors.length>0" class="error_ul">
                        <li v-for="e in errors">
                            <span>{{ e }}</span>
                        </li>
                    </ul>

                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialog=false" color="primary">
                        بستن
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "ResponseDialog",
        data(){
            return {
                dialog:false,
                errors:[],
                type:'',
                text:''
            }
        },
        mounted() {
            this.$root.$on('showResponse',(msg,type,errors)=>{
                this.dialog=true;
                this.text=msg;
                this.type=type;
                if(errors!==undefined){
                    this.errors=errors;
                }
                else{
                    this.errors=[];
                }
            });
        }
    }
</script>

<style scoped>
    .response-message{
        padding: 30px 15px 20px 15px !important;
        color: black !important;
        letter-spacing: normal !important;
        align-items: center;
        display: flex;
        font-size: 18px !important;
    }
    .error_ul{
        margin:5px 25px 5px 10px !important;
        font-size: 14px;
        padding: 0px !important;
    }
    .error_ul li{
        line-height: 25px;
    }
</style>
