<template>
    <div class="product_info product-page-skeleton">
       <v-row>

           <div class="gallery-skeleton">
               <v-skeleton-loader
                   type="image,actions"
                   :types="{'actions': 'button@4' }"
               ></v-skeleton-loader>
           </div>

           <div class="product-detail-box">
               <v-row>

                   <div class="col-8">
                       <v-skeleton-loader
                           type="list-item-three-line"
                       ></v-skeleton-loader>
                   </div>

                   <div class="col-4">
                       <v-skeleton-loader
                           type="image"
                       ></v-skeleton-loader>
                   </div>

               </v-row>
           </div>
       </v-row>
    </div>
</template>

<script>
    export default {
        name: "ProductPageSkeleton"
    }
</script>

<style>
    .gallery-skeleton{
      width: 420px;
    }
    .product-detail-box{
        width:calc(100% - 420px);
    }
    .gallery-skeleton .v-skeleton-loader__image{
        height: 300px;
        width: 75% ;
        margin:80px auto 40px auto;
    }
    .gallery-skeleton .v-skeleton-loader__button{
        width: 75px;
        height: 70px;
        margin-left: 12px;
    }
    .product-detail-box .v-skeleton-loader__image{
        height: 400px;
        margin-top:95px;
    }
    .gallery-skeleton .v-skeleton-loader__actions{
        text-align: center !important;
    }
</style>
