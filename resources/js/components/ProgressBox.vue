<template>
    <div v-if="show">
<!--        <v-progress-linear value="15"></v-progress-linear>-->
        <v-progress-linear
            indeterminate
            rounded
            height="10"
            color="#475569"
        ></v-progress-linear>
        <div class="loading_box"></div>
    </div>
</template>

<script>
export default {
    name: "Progress",
    data(){
        return{
            show:false
        }
    },
    mounted() {
        this.$root.$on('show_progress',()=>{
            this.show=true;
        });
        this.$root.$on('hide_progress',()=>{
            this.show=false;
        });
    },
}
</script>

<style scoped>
    .loading_box{
        position: fixed;
        width: 100%;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        background-color: rgba(209, 209, 209, 0.49);
    }
</style>
