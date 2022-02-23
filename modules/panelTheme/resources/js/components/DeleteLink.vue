<template>
    <div>

        <div @click="dialog=!dialog">
            <v-icon :color="color===undefined ? 'red' : color" style="margin-right: 5px">
                mdi-delete
            </v-icon>
        </div>

        <v-dialog
            v-model="dialog"
            width="450"
        >
            <v-card>
                <v-card-text>

                    <div v-if="sendTrash==='yes'" class="alert-div">
                        <span>آیا از حذف این </span>
                        <span>{{ label}}</span>
                        <span> مطمئن هستین؟</span>
                    </div>

                    <div v-else class="alert-div">
                        <span>آیا از حذف این </span>
                        <span>{{ label }} </span>
                        <span>برای همیشه مطمئن هستین؟</span>
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
        name: "DeleteLink",
        data(){
            return {
                dialog:false
            }
        },
        props:['color','row-id','label','url','sendTrash'],
        methods:{
            sendRequest:function () {
                this.$root.$emit('send_delete_request',this.url);
                this.dialog=false;
            }
        }
    }
</script>

<style scoped>

</style>
