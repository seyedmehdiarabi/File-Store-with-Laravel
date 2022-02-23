<template>
    <div>
        <div class="filter_header">
            <v-btn @click="drawer=true">
                <v-icon>mdi-filter</v-icon>
                جستجوی پیشرفته
            </v-btn>
            <v-btn @click="sortDrawer=true">
                <v-icon>mdi-sort-variant</v-icon>
                مرتب سازی
            </v-btn>
        </div>

        <div style="display: flex;flex-wrap: wrap">
            <div v-for="(item,key) in selected_filter" class="selected-filter" @click="remove_selected_filter(item,true)">
                <span>{{ item.label1}} : </span>
                <span>{{ item.label2}} </span>
                <v-icon size="12">mdi-close</v-icon>
            </div>
        </div>

        <slot name="product-list" v-if="hasFilter===false"></slot>

        <template v-if="products.data.length>0 && clientRequest===true">

            <template v-for="product in products.data">
                <slot name="layout" :product="product"></slot>
            </template>


            <div v-if="products.data.length===0 && sendProductRequest===false && clientRequest" class="not_fount_product_message">
                محصولی برای نمایش یافت نشد
            </div>
        </template>

        <template v-if="sendProductRequest">
            <div v-for="i in 2 " class="product_div" style="padding:15px">
                 <div class="product_info_div">
                     <div class="image_box">
                         <v-skeleton-loader
                             type="image"
                             height="120"
                         ></v-skeleton-loader>
                     </div>
                     <div class="product-info">
                         <v-skeleton-loader
                             type="list-item-two-line"
                         ></v-skeleton-loader>
                     </div>
                 </div>
            </div>
        </template>

        <v-navigation-drawer
          v-model="drawer"
          right
          fixed
          app
          width="100%"
          class="app-filter-box"
        >
            <v-app-bar  fixed elevation="0">
                  <div>
                      <v-icon @click="drawer=!drawer">mdi-arrow-right</v-icon>
                  </div>
                <v-toolbar-title>{{ cat_name }}</v-toolbar-title>
            </v-app-bar>

            <div style="padding-top: 40px"></div>


            <div v-if="sendRequest">
                <v-card class="filter-box">
                    <v-skeleton-loader
                        type="list-item-three-line"
                    ></v-skeleton-loader>
                </v-card>

                <v-card class="filter-box">
                    <v-skeleton-loader
                        type="list-item-three-line"
                    ></v-skeleton-loader>
                </v-card>
            </div>

            <div v-else>
                <v-expansion-panels style="padding: 20px 0px">

                    <v-expansion-panel>
                        <v-expansion-panel-header>
                            <span>فقط کالاهای موجود</span>

                            <template v-slot:actions>
                                <v-switch
                                    v-model="has_product"
                                    hide-details
                                    @click="setHasProduct"
                                ></v-switch>
                            </template>
                        </v-expansion-panel-header>
                    </v-expansion-panel>

                    <v-expansion-panel>
                        <v-expansion-panel-header>
                            <span>جست و جو در نتایج</span>
                        </v-expansion-panel-header>

                        <v-expansion-panel-content>
                            <v-text-field outlined
                                          placeholder="نام محصول  مورد نظر خود را بنوسید ..."
                                          v-model="searchTextString"
                                          hide-details
                                          dense
                                          @keyup.enter="searchProduct()"
                            ></v-text-field>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                    <v-expansion-panel>
                        <v-expansion-panel-header>
                            <span>فقط کالاهای آماده ارسال</span>
                            <template v-slot:actions>
                                <v-switch
                                    v-model="has_ready_to_shipment"
                                    hide-details
                                    @click="setHasReadyToShipment"
                                ></v-switch>
                            </template>
                        </v-expansion-panel-header>
                    </v-expansion-panel>

                    <template v-if="filterList['customItems']!==undefined">

                        <v-expansion-panel v-for="(row,key) in filterList['customItems']"  :key="key">
                            <v-expansion-panel-header>
                                <span>{{ row.title }}</span>
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <div style="margin:10px"  v-if="row.items.length>9">
                                    <v-text-field
                                        hide-details
                                        dense
                                        outlined
                                        placeholder="جست و جو ..."
                                        v-model="searchText['filter_'+row.param]"
                                    ></v-text-field>
                                </div>

                                <v-list dense>
                                    <v-list-item-group color="primary">
                                        <v-list-item v-for="(item,key2) in row.items" :key="key2"
                                                     v-if="searchText['filter_'+row.param]===undefined ||
                                                   item.title.toString().indexOf(searchText['filter_'+row.param])>-1">
                                            <v-list-item-action>
                                                <v-checkbox
                                                    color="red"
                                                    v-model="checkbox[row.param+'_'+item.id]"
                                                    @click="addFilter(row.param,item.id,row.param+'_')"
                                                >
                                                </v-checkbox>
                                            </v-list-item-action>
                                            <v-list-item-content>
                                                <div style="display: flex;justify-content: space-between">
                                                    <span style="font-size: 14px">{{ item.title }}</span>
                                                    <div v-if="item.html !==undefined" v-html="item.html"></div>
                                                </div>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>

                            </v-expansion-panel-content>
                        </v-expansion-panel>

                    </template>

                    <v-expansion-panel>
                        <v-expansion-panel-header>
                            <span>قیمت</span>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>

                           <v-range-slider
                                v-model="priceRange"
                                :max="max"
                                :min="min"
                                hide-details
                                style="margin:10px 0px"
                                @change="setRangePrice"

                            ></v-range-slider>

                            <ul class="filter_price_ul">
                                <li>
                                    <div>از</div>
                                    <div class="price">{{ replaceNumber(number_format(priceRange[0])) }}</div>
                                    <div>تومان</div>
                                </li>
                                <li>
                                    <div>تا</div>
                                    <div class="price">{{ replaceNumber(number_format(priceRange[1])) }}</div>
                                    <div>تومان</div>
                                </li>
                            </ul>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                </v-expansion-panels>
                <div style="padding-top: 40px"></div>
                <v-btn color="error" class="filter-btn" @click="filterProduct">
                    فیلتر محصولات
                </v-btn>
            </div>

        </v-navigation-drawer>

        <v-navigation-drawer
           v-model="sortDrawer"
           width="100%"
           bottom
           height="270"
           app
        >
            <v-card>
                <v-card-title style="padding-bottom: 0px">
                    مرتب سازی بر اساس
                </v-card-title>
                <v-card-text>
                    <v-radio-group v-model="sort">
                        <v-radio :value="21" @click="sortProduct()" label="پربازدید ترین"></v-radio>
                        <v-radio :value="22" @click="sortProduct()" label="محبوب ترین"></v-radio>
                        <v-radio :value="23" @click="sortProduct()" label="جدید ترین"></v-radio>
                        <v-radio :value="24" @click="sortProduct()"  label="ارزان ترین"></v-radio>
                        <v-radio :value="25" @click="sortProduct()" label="گران ترین"></v-radio>
                    </v-radio-group>
                </v-card-text>
            </v-card>
        </v-navigation-drawer>

    </div>
</template>

<script>
import SearchMethods from "../SearchMethods";

export default {
    name: "MobileFilterList",
    props:['cat_name','cat_id','max_price'],
    data(){
        return {
            clientRequest:false,
            drawer:false,
            products:{data:[]},
            filterList:{},
            sendRequest:true,
            checkbox:{},
            searchText:{},
            has_product:false,
            has_ready_to_shipment:false,
            searchTextSting:'',
            min:0,
            max:0,
            priceRange:[0,0],
            searchTextString:'',
            sendProductRequest:false,
            sortDrawer:false,
            sort:23,
            search_url:'',
            selected_filter:[],
            boxStatus:{},
            getServerData:'ok',
            page:1,
            mobileApp:true,
            hasFilter:false
        }
    },
    mixins:[SearchMethods],
    mounted() {
        this.getFilterList();
        this.scroll();

        const params=new window.URLSearchParams(window.location.search);

        if(params.get('string')!==null){
            this.searchTextSting=params.get('string');
        }
        if(this.max_price>0){
            this.max=this.max_price;
            if(params.get('price[min]')!==null){
                this.priceRange[0]=parseInt(params.get('price[min]'));
            }
            if(params.get('price[max]')!==null){
                this.priceRange[1]=parseInt(params.get('price[max]'));
            }
            else{
                this.priceRange[1]=this.max;
            }
        }
    },
    methods:{
        scroll () {
            const self=this;
            window.onscroll = () => {
                let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;
                if (bottomOfWindow && self.getServerData==='ok' && this.sendProductRequest===false) {
                    alert('a');
                    this.page=this.page+1;
                    this.add_url_param('page',this.page);
                    this.setPageUrl(this.search_url,true);
                    this.search_url='';
                }
            }
        }
    }
}
</script>

<style>
@import "../../css/style.css";
</style>
