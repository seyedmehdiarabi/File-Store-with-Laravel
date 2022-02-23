<template>
    <div>
        <div @click="dialog=!dialog">
            <v-icon color="black" style="margin-right: 5px">
                mdi-restore
            </v-icon>
        </div>

        <v-dialog
            v-model="dialog"
            width="450"
        >
            <v-card>
                <v-card-text>

                    <div  class="alert-div">
                        <span>آیا از بازیابی این </span>
                        <span>{{ label}}</span>
                        <span> مطمئن هستین؟</span>
                    </div>


                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="success"
                           @click="sendRequest"
                           class="action-btn"
                           text
                    >
                        بله
                    </v-btn>

                    <v-btn color="error"
                           @click="dialog=false"
                           class="action-btn"
                           text
                    >
                        خیر
                    </v-btn>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "RestoreLink",
        props:['row-id','label','url'],
        data(){
            return {
                dialog:false
            }
        },
        methods:{
            sendRequest:function () {
                this.$root.$emit('send_post_request',this.url,{});
                this.dialog=false;
            }
        }
    }
</script>
