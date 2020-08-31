<template>
    <!--
    <div class="page-group" @click="$parent.open_page_group(PageGroupData.meta_object_uuid)">
        <b-icon-folder></b-icon-folder> {{PageGroupData.page_group_name}}
    </div>
    -->
    <div class="container">
        <div class="row">
            <div class="col-1">
                <b-icon-folder class="clickable" @click="$parent.open_page_group(PageGroupData.meta_object_uuid)"></b-icon-folder>
            </div>
            <div class="col-9" style="text-align: left">
                <span class="clickable" @click="$parent.open_page_group(PageGroupData.meta_object_uuid)">{{PageGroupData.page_group_name}}</span>
            </div>
            <div class="col-2" v-if="typeof $parent.EmbeddedData === 'undefined' ">
                <b-icon-list-check
                        v-b-tooltip.hover title="Page group permissions"
                        variant="primary"
                        class="clickable"
                        @click="$parent.permissions_page_group(PageGroupData.meta_object_uuid, PageGroupData.page_group_name)"
                ></b-icon-list-check>
                <b-icon-pencil
                        v-b-tooltip.hover title="Edit page group"
                        variant="primary"
                        class="clickable"
                        @click="$parent.edit_page_group(PageGroupData.meta_object_uuid, PageGroupData.page_group_name)"
                ></b-icon-pencil>
                <b-icon-trash
                        v-b-tooltip.hover title="Delete page group"
                        variant="danger"
                        class="clickable"
                        @click="$parent.delete_page_group(PageGroupData.meta_object_uuid, PageGroupData.page_group_name)"
                ></b-icon-trash>
            </div>
        </div>
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
    .container {
        border: 2px solid black;
        margin: 4px;

        border-radius: 5px;
        vertical-align: center;
        font-size: 1.5em;
        text-align: left;
    }

    .clickable:hover {

    }

    .clickable {
        cursor: pointer;
    }
</style>