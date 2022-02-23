<template>
    <div :class="className">
        <v-combobox
            multiple
            :label="label"
            v-model="select"
            class="tag-input"
            chips
            deletable-chips
            @update:search-input="updateTags"
            :search-input.sync="search"
        >
        </v-combobox>
        <input type="hidden" :name="name" v-model="select">
    </div>
</template>

<script>
import methods from "../methods";

export default {
    name: "TagBox",
    props: ['name', 'label', 'value', 'args'],
    mixins: [methods],
    data() {
        return {
            select: [],
            items: [],
            search: '',
            className: 'c-field',
        }
    },
    mounted() {
        if (this.args['class'] !== undefined) {
            this.className = this.className + ' ' + this.args['class'];
        }
        if (this.value !== '') {
            const tags = this.value.toString().split(',');
            for (let i = 0; i < tags.length; i++) {
                if (tags[i].toString().trim() !== '' && tags[i].toString().trim() !== 'null') {
                    this.select.push(tags[i]);
                }
            }
        }
    },
    methods: {
        updateTags: function () {
            this.$nextTick(() => {
                if (this.search !== null && this.search.split(',').length > 1) {
                    const tags = this.search.split(',');
                    for (let i = 0; i < tags.length; i++) {
                        if (tags[i].toString().trim() !== '') {
                            this.select.push(tags[i]);
                        }
                    }
                    this.search = '';
                }
            });
        }
    }
}
</script>


