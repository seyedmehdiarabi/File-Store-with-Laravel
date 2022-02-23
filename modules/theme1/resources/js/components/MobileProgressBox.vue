<template>
    <div v-if="show">
        <div class="mobile-progress-box">
            <v-progress-circular indeterminate color="red"></v-progress-circular>
        </div>
    </div>
</template>

<script>
export default {
    name: "MobileProgressBox",
    data(){
        return {
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
    }
}
</script>


<style>
@import "../../css/style.css";
</style>
