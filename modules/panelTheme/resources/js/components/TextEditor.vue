<template>
    <div style="margin-bottom: 20px">
        <ckeditor v-model="htmlCode" :config="editorConfig"></ckeditor>
        <input type="hidden" :name="name" v-model="htmlCode"/>
    </div>
</template>

<script>
import CKEditor from 'ckeditor4-vue';
export default {
    name: "TextEditor",
    components:{
        ckeditor: CKEditor.component
    },
    props:['name','value'],
    data(){
        return {
            editorConfig: {
                language: 'fa',
                height:'300px'
            },
            htmlCode:this.value
        }
    }
}
</script>


