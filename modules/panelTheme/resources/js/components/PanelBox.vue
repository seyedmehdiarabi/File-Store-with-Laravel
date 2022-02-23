<template>
    <v-card
        elevation="2"
        :loading="loading"
        class="panel"
        :disabled="loading"
    >
        <slot></slot>
    </v-card>
</template>

<script>
    export default {
        name: "PanelBox",
        data(){
            return {
                loading:false
            }
        },
        mounted() {
            this.$root.$on('show_progress',()=>{
                this.loading=true;
            });
            this.$root.$on('hide_progress',()=>{
                this.loading=false;
            });
        }
    }
</script>

