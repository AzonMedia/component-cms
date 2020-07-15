<template>
    <div class="page" @click="$parent.open_page(PageData.meta_object_uuid)">
        {{PageData.page_name}}
    </div>
</template>

<script>
    export default {
        name: "Page",
        props: {
            PageData : Object
        },
    }
</script>

<style scoped>
    .page {
        border: 1px solid black;
        margin: 4px;
        height: 40px;
        border-radius: 5px;
        vertical-align: center;
        cursor: pointer;
    }
</style>