<template>
    <b-tab title="To Page">
        <!-- <p>Page: <v-select v-model="Link.link_class_name" :options="ModelClasses"></v-select></p> -->
        <CmsAdmin v-bind:EmbeddedData="EmbeddedData"></CmsAdmin>
    </b-tab>
</template>

<script>

    import vSelect from 'vue-select'
    import 'vue-select/dist/vue-select.css'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    import CmsAdmin from '@GuzabaPlatform.Cms/CmsAdmin.vue'

    export default {
        name: "AddLinkPage",
        // data() {
        //     return {
        //         Link : {}
        //     }
        // },
        mixins: [
            ToastMixin,
        ],
        components: {
            CmsAdmin
        },
        data() {
            return {
                EmbeddedData: {
                    //embedded: true,//no need of this... just defining the object and passing it is enough for the check inside CmsAdmin
                    open_page_group : (CmsAdminC, page_group_uuid) => {
                        CmsAdminC.get_groups_and_pages(page_group_uuid)
                    },
                    open_page : (CmsAdminC, page_uuid) => {
                        let AddLinkComponent = this.get_parent_component_by_name('AddLink')
                        AddLinkComponent.Link.link_class_name = 'GuzabaPlatform\\Cms\\Models\\Page'
                        AddLinkComponent.Link.link_object_uuid = page_uuid
                        CmsAdminC.highlighted_page_uuid = page_uuid
                    }
                }
            }
        },

    }
</script>

<style scoped>

</style>