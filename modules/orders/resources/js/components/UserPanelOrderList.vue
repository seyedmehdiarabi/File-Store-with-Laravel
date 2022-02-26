<template>

    <div class="user-panel-order-list">

        <v-tabs
            v-model="tab"
            color="error"
        >

            <v-tab v-for="(title,key) in tabs" :key="key" @click="getTabOrder(key,1)">
                <span>{{ title }}</span>
                <span class="count" v-if="order_count[tabId[key]]>0">
                     {{ replaceNumber(order_count[tabId[key]])}}
                </span>
            </v-tab>

        </v-tabs>


        <div v-if="sendRequest[tabId[tab]]===true">

            <v-skeleton-loader
               type="list-item-three-line"
            ></v-skeleton-loader>

            <v-skeleton-loader
                type="list-item-three-line"
            ></v-skeleton-loader>

            <v-skeleton-loader
                type="list-item-three-line"
            ></v-skeleton-loader>

        </div>
        <div v-else>

            <div v-if="orderList[tabId[tab]]!==undefined && orderList[tabId[tab]]['data'].length>0" >

                <div class="order-item" v-for="order in orderList[tabId[tab]]['data']" :key="order.id">

                    <div class="list-item-detail">

                        <div class="order-detail-header">

                            <div class="item-detail-row">
                                <div class="detail-item">{{ replaceNumber(order.date) }}</div>
                                <div class="detail-item">DKC-{{ replaceNumber(order.id) }}</div>
                            </div>

                            <div>
                                <a class="order-link" @click="show_order(order.id)">مشاهده سفارش</a>
                            </div>

                        </div>

                        <div class="item-detail-row">
                            <span>مبلغ کل : </span>
                            {{ order['price'] }}
                        </div>

                    </div>

                    <div class="submission" v-for="(submission,key) in order['submissions']">

                        <p>
                            <span>مرسوله </span>
                            <span>{{ replaceNumber((key+1)) }} </span>
                            <span> از</span>
                            <span>{{ replaceNumber(order['submissions'].length) }} </span>
                        </p>

                        <div class="products">
                            <template v-for="item in submission['products']">
                                <a v-if="item.product!==undefined && item.product!==null" :href="getUrl(item.product)" class="router-link">
                                    <img :src="$siteUrl+'/files/thumbnails/'+item.product.image_url" />
                                </a>
                            </template>
                        </div>

                    </div>

                </div>

                <vue-pagination
                   :pagination="orderList[tabId[tab]]"
                   @paginate="getOrder"
                   :offset="5"
                >

                </vue-pagination>

            </div>

            <div v-else>
                <p class="not-found-order">سفارشی یافت نشد</p>
            </div>
        </div>


    </div>

</template>

<script>
    import VuePagination from "./VuePagination";
    export default {
        name: "UserPanelOrderList",
        props:['orders','order_count','shop_product_url'],
        components:{
            VuePagination
        },
        data(){
            return {
                tab:'wait_for_payment',
                tabs:[
                    'در انتظار پرداخت',
                    'در حال پردازش',
                    'تحویل شده',
                    'مرجوعی',
                    'لغو شده'
                ],
                tabId:[
                    'wait_for_payment',
                    'paid_in_progress',
                    'delivered',
                    'returned',
                    'canceled'
                ],
                orderList:{},
                sendRequest:{},
                pages:{}
            }
        },
        mounted() {
            this.orderList['wait_for_payment']=this.orders;
            this.pages['wait_for_payment']=1;
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
            getUrl:function (product) {
                let url=this.shop_product_url;
                url=url.replace(':id',product.id);
                url=url.replace(':product_url',product.product_url);
                return url;
            },
            show_order:function (order_id) {
                const url=this.$siteUrl+'/user/profile/order/'+order_id;
                this.$root.$emit('send_get_request',url);
            },
            getTabOrder:function (key,page,get) {
               if(this.orderList[this.tabId[key]]===undefined || get===true){
                   this.sendRequest[this.tabId[key]]=true;
                   this.pages[this.tabId[this.tab]]=page;
                   const type=this.tabId[key].toString().replaceAll('_','-');
                   const url=this.$siteUrl+'/user/profile/order/json/list?activeTab='+type+'&page='+page;
                   this.axios.get(url).then(
                       response=>{
                           this.orderList[this.tabId[key]]=response.data;
                           this.sendRequest[this.tabId[key]]=false;
                           this.$forceUpdate();
                       }
                   ).catch(error=>{
                       this.sendRequest[this.tabId[key]]=false;
                       this.orderList[this.tabId[key]]=[];
                   });
               }
            },
            getOrder:function (page) {
                if(this.pages[this.tabId[this.tab]]!==undefined &&  this.pages[this.tabId[this.tab]]!==page){
                    this.pages[this.tabId[this.tab]]=page;
                    this.$vuetify.goTo(0);
                    this.getTabOrder(this.tab,page,true);
                }
            }
        }
    }
</script>

<style>
    @import "../style.css";
</style>
