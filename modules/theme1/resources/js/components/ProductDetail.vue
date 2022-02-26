<template>
    <div class="product-detail">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "ProductDetail",
        mounted() {
            document.querySelector('.more_content span').addEventListener('click',function () {

                if(document.querySelector('.more_content span').parentElement.classList.contains('show_short_content')){
                    document.querySelector('.more_content span').parentElement.classList.remove('show_short_content');
                    document.querySelector('.more_content span').textContent='ادامه مطلب';
                    document.querySelector('.tozihat .content div').style.maxHeight='250px';
                }
                else{
                    document.querySelector('.more_content span').parentElement.classList.add('show_short_content');
                    document.querySelector('.more_content span').textContent='بستن';
                    document.querySelector('.tozihat .content div').style.maxHeight='none';
                }
            });

            const product_tozihat=document.querySelector("#product_tozihat");
            if(product_tozihat!=undefined && product_tozihat.scrollHeight<250){
                document.querySelector('.more_content').style.display='none';
            }
        }
    }
</script>

<style scoped>
    .product-detail{
        min-height: 400px;
    }
</style>
