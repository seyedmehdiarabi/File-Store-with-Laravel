<template>
    <div v-if="items!=='0'" class="data-table">
        <v-data-table
            :headers="tableHeader"
            :items="items"
            class="elevation-1 rounded-lg"
            v-resize="onResize"
            hide-default-footer
        >
            <template v-slot:item="{item}">
                <tr v-if="!isMobile">
                    <td v-for="(row,key) in tableHeader" class="text-center">
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
            </template>
        </v-data-table>
<!--        <div class="mt-5 mx-2 pa-4 rounded-lg bg-white elevation-1" v-if="cart">
            <v-form ref="form">
                <div  class="w-100 mt-4 mx-4 row">
                    <v-text-field v-model="discount" :rules="discountRules" class="col-12 col-lg-5 col-md-5" name="discount" label="کد تخفیف" outlined></v-text-field>
                    <v-btn text color="primary" class="pa-2 mx-2 mt-3 rounded-lg cursor-pointer" @click="send_discount">ارسال</v-btn>
                </div>
            </v-form>
        </div>-->
    </div>
    <div v-else class="">رکوردی برای نمایش وجود ندارد</div>
</template>

<script>
const dynamicComponent = {
    template: '',
    props: ['template'],
    functional: true,
    render: function (h, context) {
        let template = context.props.template;
        template = '<div>' + template + '</div>';
        const component = {template};
        return h(component);
    }
};
export default {
    name: "PanelTable",
    props: ['headers', 'items','cart'],
    data() {
        return {
            tableHeader: [],
            isMobile: false,
            discount:'',
            discountRules: [
                v => !!v || 'لطفاً کد تخفیف را وارد نمایید!',
            ],
        }
    },
    mounted() {
        const href=window.location.href;
        const h = this.headers;
        for (let i = 0; i < h.length; i++) {
            h[i].value = 'column' + i;
            h[i].sortable = false;
        }
        this.tableHeader = h;
    },
    methods: {
        onResize: function () {
            if (window.innerWidth < 769) {
                this.isMobile = true;
                const data_table = document.getElementsByClassName('data-table');
                for (let i = 0; i < data_table.length; i++) {
                    data_table[i].querySelector('.v-data-table-header').style.display = 'none';
                }
            } else {
                this.isMobile = false;
                const data_table = document.getElementsByClassName('data-table');
                for (let i = 0; i < data_table.length; i++) {
                    data_table[i].querySelector('.v-data-table-header').style.display = 'contents';
                }
            }
        },
        send_discount:function () {
            if (this.$refs.form.validate()){
                this.$root.$emit('show_progress');
                const formData = new FormData();
                formData.append('discount', this.discount);
                formData.append('request-type', 'axios');
                this.axios.post(this.$siteUrl + '/send_discount', formData).then(response => {
                    console.log(response);
                    this.$root.$emit('hide_progress');
                    if(response.data['message']!==undefined){
                        const type=response.data['status']===undefined ? 'success' : response.data['status'];
                        this.$root.$emit('showResponse',response.data['message'],type);
                    }else{
                        this.comment_box=false;
                    }
                });
                this.$root.$emit('get_page_content',this.$siteUrl+'/cart');
            }
        }
    },
    components: {
        dynamicComponent
    }
}
</script>

<style>

</style>
