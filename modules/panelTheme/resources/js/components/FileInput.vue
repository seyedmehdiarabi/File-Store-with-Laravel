<template>
    <div>
        <v-file-input
          show-size
          :label="label"
          outlined
          dense
          :class="className"
          :name="name"
          :accept="validation"
        >
        </v-file-input>
    </div>
</template>

<script>
    import methods from "./../methods";

    export default {
        name: "FileInput",
        mixins:[methods],
        props:['label','name','args','validation'],
        data(){
            return {
                className:'c-field',
                rules:[]
            }
        },
        mounted() {
            if(this.args!==null){
                this.options=this.args;
                if(this.options['class']!==undefined){
                    this.className=this.className+' '+this.options['class'];
                }
                if(this.options['validate']!==undefined){
                    this.setValidate(this.options['validate']);
                }
            }
        },
    }
</script>

<style scoped>

</style>
