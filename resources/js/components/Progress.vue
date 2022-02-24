<template>
    <div v-if="show">
        <v-progress-linear indeterminate :reverse="$vuetify.rtl" height="10"></v-progress-linear>
        <div class="loading_box"></div>
    </div>
</template>

<script>
export default {
    name: "Progress",
    data() {
        return {
            show: false,
        }
    },
    mounted() {
        this.$root.$emit('show_progress',()=>{
            this.show = true;
        });
        this.$root.$emit('hide_progress',()=>{
            this.show = false;
        });
    },
}
</script>

<style scoped>
.loading_box {
    background-color: rgba(209, 209, 209, 0.49);
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 1000000;
}
</style>
