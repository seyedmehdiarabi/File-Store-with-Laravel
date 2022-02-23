<template>
    <div :class="className">
        <v-textarea
            :label="label!=='false' ? label : ''"
            outlined
            dense
            v-model="input"
            :rules="rules"
        >

        </v-textarea>
        <input type="hidden" :name="name" v-model="input"/>
    </div>
</template>

<script>
import methods from "../methods";

export default {
    name: "TextareaField",
    props: ['name', 'label', 'args', 'value'],
    mixins: [methods],
    data() {
        return {
            input: '',
            options: [],
            className: 'c-field',
            rules: []
        }
    },
    mounted() {
        if (this.value !== 'null') {
            this.input = this.value;
        }
        if (this.args !== null) {
            this.options = this.args;
            if (this.options['type'] !== undefined) {
                this.type = this.options['type'];
            }
            if (this.options['class'] !== undefined) {
                this.className = this.className + ' ' + this.options['class'];
            }
            if (this.options['validate'] !== undefined) {
                this.setValidate(this.options['validate']);
            }
        }
    }
}
</script>

<style>
@import "../style.css";
</style>

