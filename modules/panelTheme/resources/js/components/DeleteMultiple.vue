<template>
    <v-list-item>
        <v-list-item-icon>
            <v-icon :class="[active ? 'active' : 'inactive']">mdi-close</v-icon>
        </v-list-item-icon>
        <v-list-item-title>

            <v-dialog
                v-model="dialog"
                width="450"
            >
                <template v-slot:activator="{ on, attrs }">
                    <a :class="[active ? 'active' : 'inactive']"  v-bind="attrs"
                       v-on="active ? on : ''" >
                        <span>حذف {{ title }} ها</span>
                    </a>
                </template>

                <v-card>
                    <v-card-text>

                        <div class="alert-div">
                            آیا از حذف ایتم های انتخابی مطمئن هستین؟
                        </div>

                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>

                        <v-btn
                            color="success"
                            @click="sendRequest()"
                            class="action-btn"
                            text
                        >
                            بله
                        </v-btn>

                        <v-btn
                            color="error"
                            @click="dialog = false"
                            class="action-btn"
                            text
                        >
                            خیر
                        </v-btn>


                    </v-card-actions>

                </v-card>

            </v-dialog>

        </v-list-item-title>
    </v-list-item>
</template>

<script>
    export default {
        name: "DeleteMultiple",
        props:['title','route'],
        data(){
            return {
                active:false,
                dialog:false
            }
        },
        mounted() {
            this.$root.$on('addLoadEvent',()=>{
                 const inputs=document.querySelectorAll('input[aria-checked="true"]');
                 if(inputs.length>=1){
                     this.active=true;
                 }
                 else{
                     this.active=false;
                 }
            });
        },
        methods:{
            sendRequest:function () {
                const inputs=document.querySelectorAll('input[aria-checked="true"]');
                let name='';
                const array=[];
                for (let i = 0; i <inputs.length ; i++) {
                    if(i===0){
                        name=inputs[i].getAttribute('name');
                    }
                    array.push(inputs[i].getAttribute('value'));
                }

                let formData={};
                formData[name]=array;
                const href=window.location.href.split('?');
                let url=href[0]+'/remove_items';
                if(href.length===2){
                    url=url+'?'+href[1];
                }
                this.$root.$emit('send_post_request',url,formData);
                this.dialog=false;
            }
        }
    }
</script>

<style scoped>
    .active{
        color: black;
    }
    .inactive{
        color: grey;
    }
</style>
