<template>
    <v-app-bar absolute color="white" elevate-on-scroll height="60">

        <v-app-bar-nav-icon @click="$root.$emit('changeNavigation')" />

        <v-toolbar-title>پنل مدیریت</v-toolbar-title>

    </v-app-bar>

</template>

<script>
    export default {
        name: "PanelAppBar"
    }
</script>

<style scoped>

</style>
