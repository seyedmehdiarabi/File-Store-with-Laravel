<template>

    <v-card id="product_page_tabs">
         <v-tabs v-model="tab" background-color="#f7f7f7" >
             <slot name="items"></slot>
         </v-tabs>

        <v-tabs-items v-model="tab">
            <slot name="content"></slot>
        </v-tabs-items>
    </v-card>

</template>

<script>
    export default {
        name: "ProductPageTabs",
        data(){
            return {
                tab:null
            }
        }
    }
</script>

<style>
    #product_page_tabs{
        margin-top: 30px;
    }
    #product_page_tabs .v-tabs-bar{
        height: 60px !important;
    }
    #product_page_tabs .v-tab--active{
        border-left: 1px solid #e3e3e3;
        border-right: 1px solid #e3e3e3 !important;
    }
    #product_page_tabs  .v-tab{
        letter-spacing: normal !important;
        font-size: 16px;
    }
    #product_page_tabs .v-icon{
        margin-left: 5px;
    }
</style>
