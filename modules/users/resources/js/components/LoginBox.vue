<template>
    <div>
        <v-dialog v-model="dialog" width="500">
            <auth-login-box>
                <template v-slot:before-login-form>
                    <slot name="before-login-form"></slot>
                </template>
            </auth-login-box>
        </v-dialog>
    </div>
</template>

<script>
export default ({
    name: "LoginBox",
    data() {
        return {
            dialog: false
        }
    },
    mounted() {
        const self = this;
        this.$root.$on('show_desktop_login', function () {
            self.dialog = true;
        });
    }
})
</script>
