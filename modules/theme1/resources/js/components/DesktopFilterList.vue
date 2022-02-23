<template>
    <v-row>
        <v-col cols="3">
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
                <slot name="before-filter-list"></slot>

                <v-card class="filter-box" v-if="selected_filter.length>0">
                    <v-card-title class="selected-filter-title">
                        <span>فیلترهای اعمال شده</span>
                        <span style="color: red;cursor: pointer">حذف</span>
                    </v-card-title>
                    <v-card-text style="display: flex;flex-wrap: wrap ">
                        <div v-for="(item,key) in selected_filter" class="selected-filter"
                             @click="remove_selected_filter(item)">
                            <span>{{ item.label1 }} : </span>
                            <span>{{ item.label2 }} </span>
                            <v-icon size="12">mdi-close</v-icon>
                        </div>
                    </v-card-text>
                </v-card>

                <v-card class="filter-box">
                    <v-card-text style="padding:1px 10px 20px 10px !important;">
                        <v-switch
                            v-model="has_product"
                            hide-details
                            label="فقط کالاهای موجود"
                            @click="setHasProduct"
                        >

                        </v-switch>
                    </v-card-text>
                </v-card>

                <v-card class="filter-box">
                    <v-card-title style="cursor: pointer">
                        <span>جست و جو در نتایج</span>
                    </v-card-title>
                    <v-card-text style="padding: 15px 10px !important">
                        <v-text-field
                            outlined
                            hide-details
                            placeholder="نام محصول خود را بنویسید ..."
                            dense
                            @keyup.enter="searchProduct"
                            v-model="searchTextSting"
                        ></v-text-field>
                    </v-card-text>
                </v-card>

                <v-card class="filter-box">
                    <v-card-text style="padding:1px 10px 20px 10px !important">
                        <v-switch
                            v-model="has_ready_to_shipment"
                            hide-details
                            label="فقط کالاهای آماده ارسال"
                            @click="setHasReadyToShipment"
                        >
                        </v-switch>
                    </v-card-text>
                </v-card>

                <template v-if="filterList['customItems']!==undefined">
                    <v-card v-for="(row,key) in filterList['customItems']" class="filter-box" :key="key">
                        <v-card-title style="cursor: pointer" @click="changeStatus(row.param)">
                            <span>{{ row.title }}</span>
                            <v-spacer></v-spacer>
                            <v-icon v-if="boxStatus[row.param]!==true">mdi-chevron-down</v-icon>
                            <v-icon v-else>mdi-chevron-up</v-icon>
                        </v-card-title>
                        <v-card-text style="padding: 0" v-if="boxStatus[row.param]===true">
                            <div style="margin: 10px" v-if="row.items.length>9">
                                <v-text-field
                                    hide-details
                                    dense
                                    outlined
                                    placeholder="جست وجو ..."
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
                                                <span>{{ item.title }}</span>
                                                <div v-if="item.html !==undefined" v-html="item.html"></div>
                                            </div>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>

                        </v-card-text>
                    </v-card>

                </template>

                <v-card class="filter-box">
                    <v-card-title style="cursor: pointer">
                        <span>محدوده قیمت مورد نظر</span>
                    </v-card-title>
                    <v-card-text>
                        <v-range-slider
                            v-model="priceRange"
                            :max="max"
                            :min="min"
                            hide-details
                            style="margin: 10px 0px"
                            @change="setRangeSlider"
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

                        <div style="display: flex;justify-content: center;padding-top:10px">
                            <v-btn style="letter-spacing: normal" color="primary" @click="priceFilter()">
                                <v-icon>mdi-filter</v-icon>
                                اعمال محدوده قیمت
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>

            </div>
        </v-col>

        <v-col cols="9">
            <slot name="before-product-list"></slot>
            <div class="product_list_box">
                <div class="header">
                    <ul class="sort-ul">
                        <li>
                            <v-icon>mdi-sort</v-icon>
                            مرتب سازی بر اساس :
                        </li>
                        <li :class="sort===21 ? 'active' : ''" v-on:click="set_sort(21)">
                            <a><span>پربازدید ترین</span></a></li>
                        <li :class="sort===22 ? 'active' : ''" v-on:click="set_sort(22)"><a><span>محبوب ترین</span></a>
                        </li>
                        <li :class="sort===23 ? 'active' : ''" v-on:click="set_sort(23)"><a><span>جدید ترین</span></a>
                        </li>
                        <li :class="sort===24 ? 'active' : ''" v-on:click="set_sort(24)"><a><span>ارزان ترین</span></a>
                        </li>
                        <li :class="sort===25 ? 'active' : ''" v-on:click="set_sort(25)"><a><span>گران ترین</span></a>
                        </li>
                    </ul>
                </div>
                <div class="search_product_div product_list">
                    <slot name="product-list"
                          v-if="products.data.length===0 && sendProductRequest===false && clientRequest===false"></slot>
                    <template v-if="sendProductRequest">
                        <div v-for="i in 8 " class="product_div" style="padding:15px">
                            <v-skeleton-loader
                                type="image,list-item-two-line"
                            ></v-skeleton-loader>
                        </div>
                    </template>
                    <template v-else>
                        <template v-for="product in products.data">
                            <slot name="layout" :product="product"></slot>
                        </template>

                        <div class="product-list-paginate" v-if="products.data.length>0">
                            <product-paginate
                                :pagination="products"
                                :offset="5"
                                @search_product_paginate="paginate"
                            ></product-paginate>
                        </div>

                        <div v-if="products.data.length===0 && sendProductRequest===false && clientRequest" class="not_fount_product_message">
                            <img v-bind:src="$siteUrl+'/files/images/box-empty.png'" alt="">
                            محصولی برای نمایش یافت نشد
                        </div>
                    </template>
                </div>
            </div>
        </v-col>
    </v-row>
</template>

<script>
import SearchMethods from "../SearchMethods";
import ProductPaginate from "./ProductPaginate";

export default {
    name: "DesktopFilterList",
    components: {ProductPaginate},
    data() {
        return {
            sendRequest: true,
            sendProductRequest: false,
            products: {data: []},
            filterList: {},
            boxStatus: {
                'brand': true
            },
            checkbox: {},
            searchText: {},
            has_product: false,
            has_ready_to_shipment: false,
            searchTextSting: '',
            min: 0,
            max: 0,
            priceRange: [0, 0],
            sort: 23,
            clientRequest: false,
            selected_filter: []
        }
    },
    mixins: [SearchMethods],
    props: ['cat_id', 'max_price'],
    mounted() {
        this.getFilterList();
        const self = this;
        this.$nextTick(function () {
            const tags = document.getElementsByClassName('search-product');
            for (let i = 0; i < tags.length; i++) {
                const href = tags[i].href;
                tags[i].addEventListener('click', function (event) {
                    event.preventDefault();
                    self.setPageUrl(href);
                })
            }
        });

        const params = new window.URLSearchParams(window.location.search);

        if (params.get('string') !== null) {
            this.searchTextSting = params.get('string');
        }
        if (this.max_price > 0) {
            this.max = this.max_price;
            if (params.get('price[min]') !== null) {
                this.priceRange[0] = parseInt(params.get('price[min]'));
            }
            if (params.get('price[max]') !== null) {
                this.priceRange[1] = parseInt(params.get('price[max]'));
            } else {
                this.priceRange[1] = this.max;
            }
        }

    }
}
</script>

<style>
@import "../../css/style.css";
</style>
