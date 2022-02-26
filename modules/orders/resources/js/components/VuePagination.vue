<template>
    <div>
        <ul class="pagination" v-if="pagesNumber.length>1">

            <li v-if="pagination.current_page>1" class="page-item">
                <a v-on:click.prevent="changePage((pagination.current_page-1))" class="page-link">
                    <span>قبلی</span>
                </a>
            </li>

            <li v-for="page in pagesNumber" :key="page" :class="[page===pagination.current_page ? 'page-item active' : 'page-item']">
                <a v-on:click.prevent="changePage(page)" class="page-link">
                    <span>{{ replaceNumber(page)}}</span>
                </a>
            </li>

            <li v-if="pagination.last_page>pagination[(pagesNumber.length-1)]" class="page-item">
                <a  class="page-link">
                    <span>...</span>
                </a>
            </li>

            <li v-if="pagination.last_page>pagination[(pagesNumber.length-1)]" class="page-item">
                <a v-on:click.prevent="changePage(pagination.last_page)" class="page-link">
                    <span>{{ replaceNumber(pagination.last_page)}}</span>
                </a>
            </li>

            <li v-if="pagination.current_page<pagination.last_page" class="page-item">
                <a v-on:click.prevent="changePage((pagination.current_page+1))" class="page-link">
                    <span>بعدی</span>
                </a>
            </li>

        </ul>
    </div>
</template>

<script>
    export default {
        name: "VuePagination",
        props:{
            pagination:{
                type:Object,
                required:true
            },
            offset:{
                type:Number,
                default:4
            }
        },
        data(){
            return {
                last:0
            }
        },
        computed:{
            pagesNumber(){
                let pagesArray=[];
                if(this.pagination.to){
                    let from=this.pagination.current_page-this.offset;
                    if(from<1){
                        from=1;
                    }
                    let to=from+(this.offset*2);
                    if(to>=this.pagination.last_page){
                        to=this.pagination.last_page;
                    }

                    for (let page =from; page <=to ; page++) {
                        pagesArray.push(page);
                    }
                }
                return pagesArray;
            }
        },
        methods:{
            replaceNumber: function (n) {
                if (n !==undefined) {
                    n = n.toString();
                    const find = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
                    const replace = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
                    for (let i = 0; i < find.length; i++) {
                        n = n.replace(new RegExp(find[i], 'g'), replace[i]);
                    }
                    return n;
                }
            },
            changePage:function (page) {
                this.pagination.current_page=page;
                this.$emit('paginate',page);
            }
        }
    }
</script>


