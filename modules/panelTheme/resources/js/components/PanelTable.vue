<template>
    <div v-if="items!=='0'" class="data-table">
        <v-data-table
            :headers="tableHeader"
            :items="items"
            class="elevation-1"
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
    props: ['headers', 'items'],
    data() {
        return {
            tableHeader: [],
            isMobile: false,
        }
    },
    mounted() {
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
        }
    },
    components: {
        dynamicComponent
    }
}
</script>

<style>

</style>
