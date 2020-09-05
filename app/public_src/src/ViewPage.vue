<template>
    <div class="view_page">
        <h2>{{ObjectData.page_name}}</h2>
        <span v-html="ObjectData.page_content"></span>
    </div>
</template>

<script>
    export default {
        name: "ViewPage",
        props: {
            ObjectData : Object
        },
    }
</script>

<style scoped>

</style>