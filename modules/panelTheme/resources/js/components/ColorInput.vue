<template>
    <div :class="className">
        <v-text-field
           v-model="input"
           outlined
           dense
           :rules="rules"
           :label="label!=='false' ? label : ''"
        >

            <template v-slot:append>
                   <v-menu v-model="menu" :close-on-content-click="false">
                       <template v-slot:activator="{ on, attrs }">
                           <div :style="swatchStyle" v-on="on"></div>
                       </template>
                       <v-card>
                           <v-card-text class="pa-0">
                               <v-color-picker flat v-model="input"></v-color-picker>
                           </v-card-text>
                       </v-card>
                   </v-menu>
            </template>

        </v-text-field>

        <input type="hidden" :name="name" v-model="input.toString().replace('#','')" />
    </div>
</template>

<script>
    import methods from "../methods";
    export default {
        name: "ColorInput",
        props:['value','label','name','args'],
        mixins:[methods],
        data(){
            return {
                className:'c-field',
                input:'',
                rules:[],
                menu:false
            }
        },
        mounted() {
            if(this.value!==''){
                this.input='#'+this.value;
            }
            if(this.value==='null'){
                this.input='';
            }
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
        computed:{
            swatchStyle(){
                const {input,menu}=this;
                return {
                    backgroundColor:input,
                    cursor:'pointer',
                    height:'30px',
                    width:'30px',
                    borderRadius:menu ? '50%' : '4px',
                    border:'1px solid rgba(187,187,187,0.38)',
                    transition:'border-radius 200ms ease-in-out'
                }
            }
        }
    }
</script>

<style scoped>

</style>
