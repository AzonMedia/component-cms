<template>
    <div class="page-group" @click="$parent.open_page_group(PageGroupData.meta_object_uuid)">
        {{PageGroupData.page_group_name}}
    </div>
</template>

<script>
    export default {
        name: "PageGroup",
        props: {
            PageGroupData : Object
        },
    }
</script>

<style scoped>
.page-group {
    border: 1px solid black;
    margin: 4px;
    height: 40px;
    border-radius: 5px;
    vertical-align: center;
    cursor: pointer;
}
</style>