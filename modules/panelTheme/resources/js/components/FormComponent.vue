<template>
    <v-form
       v-model="valid"
       :ref="form_id"
       :id="form_id+'c'"
       :class="css_class"
    >
        <slot/>
    </v-form>
</template>

<script>
    export default {
        name: "FormComponent",
        props:['form_id','css_class','type','method','request_url'],
        data(){
            return {
                valid:false
            }
        },
        mounted() {
            this.$root.$on(this.form_id+'_validate',()=>{
                this.$refs[this.form_id].validate();
                if(this.valid){
                    const inputs=document.querySelectorAll('#'+this.form_id+'c input');
                    if(this.method==='get'){
                        const url=this.setGetRequestUrl(inputs);
                        this.$root.$emit('send_get_request',url);
                    }
                    else{
                        let sendfile=false;
                        const formData=new FormData();
                        for (let i = 0; i <inputs.length ; i++) {
                            const name=inputs[i].getAttribute('name');
                            if(name!==null){
                                let value=inputs[i].getAttribute('value');
                                if(inputs[i].getAttribute('type')==='file'){
                                    value=inputs[i].files[0];
                                    if(value!=null){
                                        sendfile=true;
                                        formData.append(name,value);
                                    }
                                }
                                else{
                                    formData.append(name,value);
                                }
                            }
                        }
                        if(this.type==='edit'){
                            formData.append('_method','PUT');
                        }
                        let headers=undefined;
                        if(sendfile){
                            headers={
                                'Content-Type':'multipart/form-data'
                            }
                        }

                        this.$root.$emit('send_post_request',this.request_url,formData,headers);
                    }
                }
            });
        },
        methods:{
            setGetRequestUrl:function (inputs) {
                let url=this.request_url;
                for (let i = 0; i <inputs.length ; i++) {
                    const name=inputs[i].getAttribute('name');
                    let value=inputs[i].getAttribute('value');
                    if(name!==null){
                        value=(value===null) ? '' : value;
                        if(url.toString().indexOf('?')>-1){
                            url=url+'&'+name+'='+value;
                        }
                        else{
                            url=url+'?'+name+'='+value;
                        }
                    }
                }
                return url;
            }
        }
    }
</script>

<style scoped>

</style>
