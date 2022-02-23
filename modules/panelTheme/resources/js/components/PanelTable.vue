<template>
   <div class="data-table">
       <v-data-table
           :headers="tableHeader"
           :items="items"
           class="data-table"
           v-resize="onResize"
           hide-default-footer
       >

           <template v-slot:item="{item}">
               <tr v-if="!isMobile" @contextmenu="show">
                   <td @contextmenu="setRowId(item.id)" v-for="(row,key) in tableHeader" :class="key===0 ? 'first-td' : ''" style="text-align: center">
                       <dynamic-component :template="item['column'+key]"></dynamic-component>
                   </td>
               </tr>
               <tr v-else>
                   <td>
                       <ul class="table-ul">
                           <li v-for="(row,key) in tableHeader">
                               <span class="li-title">{{ row.text }}</span>
                               <dynamic-component :template="item['column'+key]"></dynamic-component>
                           </li>
                       </ul>
                   </td>
               </tr>
               <v-menu
                 v-model="showMenu"
                 absolute
                 offset-y
                 :position-x="x"
                 :position-y="y"
                 v-if="Object.keys(right_click).length>0"
                 content-class="right-menu-box"
               >
                   <v-list>
                       <v-list-item v-for="(event,key) in right_click" :key="key">
                          <v-list-item-title>
                              <a @click="get_page_content(event)">
                                  <v-icon>{{ event['icon'] }}</v-icon>
                                  {{ event['label'] }}
                              </a>
                          </v-list-item-title>
                       </v-list-item>
                   </v-list>
               </v-menu>
           </template>

       </v-data-table>
   </div>
</template>

<script>
    const dynamicComponent={
        template:'',
        props:['template'],
        functional:true,
        render:function (h,context) {
            let template=context.props.template;
            template='<div>'+template+'</div>';
            const component={ template };
            return h(component);
        }
    };
    export default {
        name: "PanelTable",
        props:['headers','items','right_click'],
        data () {
            return {
                tableHeader:[],
                isMobile:false,
                showMenu:false,
                x:0,
                y:0,
                rowId:''
            }
        },
        mounted() {
            const h=this.headers;
            for (let i = 0; i <h.length; i++) {
                h[i].value='column'+i;
                h[i].sortable=false;
            }
            this.tableHeader=h;
            this.$nextTick(function(){
               this.$root.$emit('addLoadEvent','data-table');
            });
        },
        methods:{
            onResize:function () {
                if(window.innerWidth<769){
                    this.isMobile=true;
                    const data_table=document.getElementsByClassName('data-table');
                    for (let i = 0; i <data_table.length ; i++) {
                        data_table[i].querySelector('.v-data-table-header').style.display='none';
                    }
                }
                else{
                    this.isMobile=false;
                    const data_table=document.getElementsByClassName('data-table');
                    for (let i = 0; i <data_table.length ; i++) {
                        data_table[i].querySelector('.v-data-table-header').style.display='contents';
                    }
                }
            },
            get_page_content:function (event) {
                let url_param=event.url;
                url_param=url_param.replace(':id',this.rowId);
                let url=this.$siteUrl+"/"+url_param;
                this.$root.$emit('send_get_request',url);
            },
            setRowId:function (id) {
                this.rowId=id;
            },
            show:function (e) {
                e.preventDefault();
                this.showMenu=false;
                this.x=e.clientX;
                this.y=e.clientY;
                this.$nextTick(function () {
                    this.showMenu=true;
                })
            }
        },
        components:{
            dynamicComponent
        }
    }
</script>

<style>
    .v-data-table__wrapper{
        box-shadow:none !important;
        -webkit-box-shadow:none !important;
        border:1px solid rgba(153,153,153,0.2);
    }
    .first-td{
        width: 50px !important;
        margin: auto;
    }
    .v-menu__content{
        box-shadow: none !important;
        border: 1px solid rgba(153,153,153,0.2);
    }
    .right-menu-box .v-list-item__title{
        padding: 10px 20px !important;
    }
    .right-menu-box .v-list-item__title a{
        color: #636363 !important;
    }
</style>
