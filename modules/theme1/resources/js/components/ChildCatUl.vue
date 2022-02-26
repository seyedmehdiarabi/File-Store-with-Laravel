<template>
    <div>
        <slot></slot>
        <p @click="showAll()" v-if="view==='short'" class="show-other-cat">
            <v-icon size="15">mdi-plus</v-icon>
            <span>موارد بیشتر</span>
        </p>

        <p @click="showShort()" v-if="view==='all'" class="show-other-cat">
            <v-icon size="15">mdi-minus</v-icon>
            <span>موارد کمتر</span>
        </p>
    </div>
</template>

<script>
export default {
    name: "ChildCatUl",
    props:['id'],
    data(){
        return {
            view:'short'
        }
    },
    mounted() {
        this.showShort();
    },
    methods:{
        showAll:function (){
            const tags=document.querySelectorAll('#'+this.id+' li');
            for (let i = 0; i < tags.length; i++) {
                if(i>=5){
                    tags[i].style.display='block';
                }
            }
            this.view='all';
        },
        showShort:function (){
            const tags=document.querySelectorAll('#'+this.id+' li');
            for (let i = 0; i < tags.length; i++) {
                if(i>=5){
                    tags[i].style.display='none';
                }
            }
            this.view='short';
        }
    }
}
</script>


