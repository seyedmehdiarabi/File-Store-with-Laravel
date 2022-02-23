<template>
    <div>
        <v-checkbox
          :label="label"
          v-model="checkbox"
        >

        </v-checkbox>
        <input type="hidden" v-model="checkbox" :name="name" v-if="checkbox"/>
    </div>
</template>

<script>
    export default {
        name: "CheckBox",
        props:['label','args','value','name'],
        data(){
            return {
                checkbox:null
            }
        },
        mounted() {
            this.checkbox=this.value;
            if(this.value==='0'){
                this.checkbox=false;
            }
        }
    }
</script>

<style scoped>

</style>
