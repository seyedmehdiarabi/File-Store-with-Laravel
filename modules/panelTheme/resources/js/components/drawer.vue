<template>
    <v-navigation-drawer
        class="navigation-drawer"
        width="270"
        absolute
        dark
        app
        right
        permanent
        :mini-variant.sync="mini">

        <div class="search-box" v-if="mini===false">
            <v-text-field v-model="searchText" placeholder="جست و جو ..."></v-text-field>
        </div>

        <v-list class="panel-menu" dense>

            <v-list-group v-for="(item,key) in menus"
                        :key="key"
                        @click="setActiveMenu(key,item)"
                        :append-icon="item.child===undefined ? '' : '$expand'"
                        v-model="item.active"
                        no-action color="rebeccapurple"
            >

                <template v-slot:activator>

                    <v-list-item-icon class="admin_menu_icon">
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title v-if="item.child===undefined">
                            <a :href="item.url"
                               :class="[item.url !== $siteUrl ? 'router-link' : '']"
                               :target="[item.url !== $siteUrl ? '_self' : '_blank']"
                            >
                                {{ item.label }}
                            </a>
                        </v-list-item-title>
                        <v-list-item-title v-else>{{ item.label }}</v-list-item-title>
                    </v-list-item-content>

                </template>
                <v-list-item v-for="(child,key2) in item.child" :key="key2">

                    <v-list-item-content>
                        <v-list-item-title>
                            <a :href="child.url" :class="[child.target ===undefined? 'router-link' : '']">{{ child.label }}
                            </a>
                        </v-list-item-title>
                    </v-list-item-content>

                </v-list-item>

            </v-list-group>

        </v-list>

    </v-navigation-drawer>

</template>

<script>
    export default {
        name: "drawer",
        props:['items'],
        data(){
            return {
                searchText:'',
                miniStatus:false,
                miniManual:false
            }
        },
        mounted() {
            const self=this;
            this.setDefaultActiveItem();
            setTimeout(function () {
                self.setHeight();
            },100);
            setTimeout(function () {
                self.setHeight();
            },1000);
            this.$root.$on('changeNavigation', () => {
                self.mini=!self.mini;
            });
            this.$nextTick(function () {
                this.$root.$emit('addLoadEvent','navigation-drawer');
            });
        },
        computed:{
            menus(){
                const list=[];
                const keys=Object.keys(this.items);
                for (let i = 0; i <keys.length ; i++) {
                    if(this.items[keys[i]].label.toString().indexOf(this.searchText)!==-1){
                        list.push(this.items[keys[i]]);
                    }
                }
                return list;
            },
            mini:{
                get:function () {
                    switch (this.$vuetify.breakpoint.name) {
                        case 'xs':
                            return  this.miniManual ? this.miniStatus : true;
                        case 'sm':
                            return  this.miniManual ? this.miniStatus : true;
                        case 'md':
                            return  this.miniManual ? this.miniStatus : true;
                        case 'lg':
                            return  this.miniManual ? this.miniStatus : false;
                        case 'xl':
                            return  this.miniManual ? this.miniStatus : false;
                    }
                },
                set:function (newValue) {
                    this.miniManual=true;
                    this.miniStatus=newValue;
                }
            }
        },
        methods:{
            setActiveMenu:function (key,item) {
                const self=this;
                setTimeout(function () {
                    self.setHeight();
                    self.$nextTick(function () {
                        this.$root.$emit('addLoadEvent','navigation-drawer');
                    });
                },400);
            },
            setHeight:function (h) {
                let scrollHeight=document.querySelector('.v-navigation-drawer__content').scrollHeight;
                let bodyHeight=document.body.scrollHeight;
                if(bodyHeight>scrollHeight){
                    scrollHeight=bodyHeight;
                }
                document.querySelector('.navigation-drawer').style.minHeight=scrollHeight+'px';
            },
            setDefaultActiveItem:function () {
                const keys=Object.keys(this.items);
                const pageUrl=window.location.href;
                for (let i = 0; i <keys.length ; i++) {
                    if(this.items[keys[i]].url!==undefined){
                        if(this.items[keys[i]].url===pageUrl){
                            this.items[keys[i]].active=true;
                        }
                    }
                    else if(this.items[keys[i]].child!==undefined){
                        const child=this.items[keys[i]].child;
                        for (let j = 0; j <child.length ; j++) {
                            if(child[j].url===pageUrl){
                                this.items[keys[i]].active=true;
                            }
                        }
                    }
                }
                this.$forceUpdate();
            }
        }
    }
</script>

<style>
    @import "../style.css";
</style>
