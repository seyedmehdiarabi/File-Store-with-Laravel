<template>
    <div class="header-search">
        <v-text-field
           placeholder="جستجو در فروشگاه"
           hide-details
           v-model="text"
           v-on:keyup.enter="onEnter"
           clearable
           filled
           prepend-inner-icon="mdi-magnify"
        ></v-text-field>
    </div>
</template>

<script>
export default {
    name: "HeaderSearch",
    data(){
        return {
            text:''
        }
    },
    methods:{
        onEnter:function (){
            if(this.text.trim().length>=2){
                const url=this.$siteUrl+'/search?string='+this.text;
                this.$root.$emit('send_get_request',url);
            }
        }
    }
}
</script>
