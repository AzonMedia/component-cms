<template>
        <!--
        <b-icon-file-text></b-icon-file-text>
        {{PageData.page_name}}
        <b-icon-list-check variant="primary"></b-icon-list-check>
        <b-icon-pencil variant="primary"></b-icon-pencil>
        <b-icon-trash variant="danger"></b-icon-trash>
        -->
        <div class="container">
            <div class="row">
                <div class="col-1">
                    <b-icon-file-text class="clickable" @click="$parent.open_page(PageData.meta_object_uuid)"></b-icon-file-text>
                </div>
                <div class="col-9" style="text-align: left">
                    <span class="clickable" @click="$parent.open_page(PageData.meta_object_uuid)">{{PageData.page_name}}</span>
                </div>
                <div class="col-2">
                    <!--
                    <b-icon-list-ol
                            v-b-tooltip.hover title="Page revisions"
                            variant="primary"
                            class="clickable"
                            @click="$parent.revisions_page(PageData.meta_object_uuid, PageData.page_name)"
                    ></b-icon-list-ol>
                    -->
                    <b-icon-list-check
                            v-b-tooltip.hover title="Page permissions"
                            variant="primary"
                            class="clickable"
                            @click="$parent.permissions_page(PageData.meta_object_uuid, PageData.page_name)"
                    ></b-icon-list-check>
                    <b-icon-pencil
                            v-b-tooltip.hover title="Edit page"
                            variant="primary"
                            class="clickable"
                            @click="$parent.edit_page(PageData.meta_object_uuid, PageData.page_name)"
                    ></b-icon-pencil>
                    <b-icon-trash
                            v-b-tooltip.hover title="Delete page"
                            variant="danger"
                            class="clickable"
                            @click="$parent.delete_page(PageData.meta_object_uuid, PageData.page_name)"
                    ></b-icon-trash>
                </div>
            </div>
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
    .container {
        border: 1px solid black;
        margin: 4px;

        border-radius: 5px;
        vertical-align: center;
        font-size: 1.5em;
        text-align: left;
    }

    .clickable {
        cursor: pointer;
    }
</style>