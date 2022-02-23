<template>
    <div :class="className">
        <v-text-field
           :label="label!=='false' ? label : ''"
           outlined
           :dense="dense"
           :type="type"
           v-model="input"
           :rules="rules"
           :suffix="suffix"
           :prepend-inner-icon="prepend_icon"
           :min="min"
        ></v-text-field>
        <input type="hidden" :name="name" v-model="input" />
    </div>
</template>

<script>
    import methods from "./../methods";
    export default {
        name: "TextInput",
        props:['name','label','args','value','input_type'],
        mixins:[methods],
        data(){
            return {
                input:'',
                options:[],
                type:"string",
                className:'c-field',
                rules:[],
                prepend_icon:'',
                suffix:'',
                min:'',
                dense:true
            }
        },
        mounted() {
            if(this.input_type!==undefined){
                this.type=this.input_type;
            }
            this.input=this.value;
            if(this.value==='null'){
                this.input='';
            }
            if(this.args!==null){
                this.options=this.args;
                if(this.options['type']!==undefined){
                    this.type=this.options['type'];
                }
                if(this.options['suffix']!==undefined){
                    this.suffix=this.options['suffix'];
                }
                if(this.options['min']!==undefined){
                    this.min=this.options['min'];
                }
                if(this.options['class']!==undefined){
                    this.className=this.className+' '+this.options['class'];
                }
                if(this.options['validate']!==undefined){
                    this.setValidate(this.options['validate']);
                }
                if(this.options['prepend']!==undefined){
                    this.prepend_icon=this.options['prepend'];
                }
                if(this.options['dense']!==undefined){
                    this.dense=this.options['dense'];
                }
            }
        }
    }
</script>

<style>
    @import "../style.css";
</style>
