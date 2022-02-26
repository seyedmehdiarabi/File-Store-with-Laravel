<template>
    <div class="product_div">
        <slot name="begin-search-product-box"></slot>
        <div class="product_info_div">
            <div class="image_box">
                <div v-if="product.status===1 && product.first_product_price!=null">
                             <span class="discount-badge" v-if="product.first_product_price.price1!==product.first_product_price.price2">
                                 {{ '٪'+replaceNumber(getDiscountValue(product.first_product_price.price1,product.first_product_price.price2)) }}
                             </span>
                </div>
                <img :class="[product.status===-1 ? 'stop-production-img' : '']" v-bind:src="$siteUrl+'/files/thumbnails/'+product.image_url">
            </div>
            <div class="product-info">
                <a class="router-link" @click="goToProductPage(product)">
                    <p class="product-title">{{ product['title'] }}</p>
                </a>

                <div v-if="product.status===1 && product.first_product_price!=null" class="price">

                    <div class="discount_div">
                        <template  v-if="product.first_product_price.price1!=product.first_product_price.price2">
                            <del>
                                {{ replaceNumber(number_format(product.first_product_price.price1)) }}
                            </del>
                        </template>
                    </div>
                    <span>{{ replaceNumber(number_format(product.first_product_price.price2))+' تومان'}}</span>
                </div>
                <div v-else class="product_status">
                    <div>
                        <p class="line"></p>
                        <span v-if="product.status===0">ناموجود</span>
                        <span v-if="product.status===-1">توقف تولید</span>
                        <span v-else>ناموجود</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SearchMethods from "../SearchMethods";
export default {
    name: "MobileProductBox",
    props:['product','product_url'],
    mixins:[SearchMethods]
}
</script>
