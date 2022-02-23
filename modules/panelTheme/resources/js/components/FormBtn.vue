<template>
    <v-btn
       :color="type==='edit' ? 'primary' : 'success'"
       @click="validate()"
    >
        {{ text }}
    </v-btn>
</template>

<script>
    export default {
        name: "FormBtn",
        props:['form_id','type','text'],
        methods:{
            validate:function () {
                const v=this.form_id+"_validate";
                this.$root.$emit(v);
            }
        }
    }
</script>

<style scoped>

</style>
