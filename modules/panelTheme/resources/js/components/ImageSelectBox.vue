<template>
    <div :class="className">
        <v-file-input
           v-model="file"
           :label="label"
           outlined
           dense
           accept="image/*"
           @change="showImg"
           :rules="rules"
           :name="name"
        >
        </v-file-input>

        <img :src="src" style="max-height:300px;margin: auto;display: table !important;max-width:100%" />

    </div>
</template>

<script>
    import methods from "../methods";
    export default {
        name: "ImageSelectBox",
        props:['label','name','args','default'],
        mixins:[methods],
        data(){
            return {
                className:'c-field',
                file:null,
                src:'',
                rules:[],
            }
        },
        mounted() {
            this.src=this.default;
            if(this.args!=null){
                this.options=this.args;
                if(this.options['validate']!==undefined){
                    this.setValidate(this.options['validate']);
                }
                if(this.options['class']!==undefined){
                    this.className= this.className+' '+this.options['class'];
                }
            }
        },
        methods:{
            showImg:function () {
                if(this.file!=null){
                    this.src=URL.createObjectURL(this.file);
                }
                else{
                    this.src=null;
                }
            }
        }
    }
</script>

