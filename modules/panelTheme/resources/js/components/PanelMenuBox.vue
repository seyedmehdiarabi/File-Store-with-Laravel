<template>
    <div class="panel-menu-box">
        <v-menu v-model="menu" offset-y min-width="200px" @input="change">
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    v-bind="attrs"
                    v-on="on"
                >
                    گزینه ها
                </v-btn>
            </template>

            <v-list class="panel-menu-box-list">
                <slot name="items"></slot>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
    export default {
        name: "PanelMenuBox",
        data(){
            return {
                menu:false
            }
        },
        methods:{
            change:function () {
                this.$nextTick(function () {
                    this.$root.$emit('addLoadEvent','panel-menu-box');
                });
            }
        }
    }
</script>
