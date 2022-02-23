<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "ShopNav",
        mounted() {
           const shop_nav_li=document.getElementsByClassName('shop_nav_li');
            for (let i = 0; i <shop_nav_li.length ; i++) {
                let el=shop_nav_li[i];
                el.addEventListener('mouseover',function () {
                    const li_width=el.offsetWidth;
                    const ul_width=document.querySelector(".shopCategory").offsetWidth;
                    const right=ul_width-el.offsetLeft-li_width;
                  //  document.querySelector('.cat_hover').style.width=li_width+'px';
                   // document.querySelector('.cat_hover').style.right=right+'px';
                   // document.querySelector('.cat_hover').style.transform='scaleX(1)';
                });
                el.addEventListener('mouseleave',function () {
                   // document.querySelector('.cat_hover').style.transform='scaleX(0)';
                });
            }

            document.querySelector("#show_cat_list").addEventListener('mouseover',function () {
                document.querySelector("#show_cat_list .cat-list-div").style.display='block';
                //document.querySelector(".category_cover").style.display='block';
            });

            document.querySelector("#show_cat_list").addEventListener('mouseleave',function () {
                document.querySelector("#show_cat_list .cat-list-div").style.display='none';
                //document.querySelector(".category_cover").style.display='none';

                const child_list_div=document.querySelectorAll('.child_list_div');
                for (let j = 0; j <child_list_div.length ; j++) {
                    child_list_div[j].style.display='none';
                }
                const cat_list_li=document.querySelectorAll('.cat_list li');
                for (let j = 0; j <cat_list_li.length ; j++) {
                    cat_list_li[j].classList.remove('active');
                }
                document.querySelector('.category-list-0').style.display='block';
                document.querySelector('.cat_list [data-index=\'0\']').classList.add('active');
            });

            const cat_list_li=document.querySelectorAll('.cat_list li');
            for (let i = 0; i < cat_list_li.length; i++) {
                cat_list_li[i].addEventListener('mouseover',function (){
                     const index=this.getAttribute('data-index');
                     const child_list_div=document.querySelectorAll('.child_list_div');
                     for (let j = 0; j <child_list_div.length ; j++) {
                          child_list_div[j].style.display='none';
                     }
                     document.querySelector('.category-list-'+index).style.display='block';
                     for (let j = 0; j <cat_list_li.length ; j++) {
                          cat_list_li[j].classList.remove('active');
                     }
                    cat_list_li[i].classList.add('active');
                })
            }
        }
    }
</script>

<style scoped>

</style>
