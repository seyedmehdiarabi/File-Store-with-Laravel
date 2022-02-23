<template>
    <div :class="className">

        <v-combobox
            v-model="select"
            dense
            outlined
            :items="items"
            class="c-combobox"
            return-object
            item-text="title"
            @input="change"
            item-color="white"
            :label="label!=='false' ? label : ''"
            :rules="rules"
        >

        </v-combobox>

        <input type="hidden" :value="inputValue" :name="name">
    </div>
</template>

<script>
    import methods from "./../methods";
    export default {
        name: "ComoBox",
        props:['name','label','args','value','list'],
        mixins:[methods],
        data(){
            return {
                select:null,
                items:[],
                inputValue:'',
                className:'c-field',
                options:[],
                rules:[]
            }
        },
        mounted() {
            this.input=this.value;
            if(this.args!==null){
                this.options=this.args;
                if(this.options['class']!==undefined){
                    this.className=this.className+' '+this.options['class'];
                }
                if(this.options['validate']!==undefined){
                    this.setValidate(this.options['validate']);
                }
            }
            this.setList();
        },
        methods:{
            setList:function () {
                const keys=Object.keys(this.list);
                let newList=[];
                for (let i = 0; i <keys.length ; i++) {
                    newList.push({
                        'id':keys[i],
                        'title':this.list[keys[i]]
                    });
                    if(keys[i]===this.value){
                       this.select=this.list[keys[i]];
                    }
                }
                this.items=newList;
                this.inputValue=this.value;
            },
            change:function (value) {
                this.inputValue=value['id'];
            }
        }
    }
</script>

<style scoped>

</style>
