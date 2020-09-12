<template>
    <b-tab title="To Page">
        <!-- <p>Page: <v-select v-model="Link.link_class_name" :options="ModelClasses"></v-select></p> -->
        <CmsAdminC v-bind:EmbeddedData="EmbeddedData"></CmsAdminC>
    </b-tab>
</template>

<script>

    //import vSelect from 'vue-select'
    //import 'vue-select/dist/vue-select.css'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    import CmsAdminC from '@GuzabaPlatform.Cms/CmsAdmin.vue'

    export default {
        name: "AddLinkPage",
        mixins: [
            ToastMixin,
        ],
        components: {
            CmsAdminC
        },
        data() {
            return { //the return data contains methods
                EmbeddedData: {
                    //embedded: true,//no need of this... just defining the object and passing it is enough for the check inside CmsAdmin
                    /**
                     * @param Vue CmsAdminC
                     * @param string page_group_uuid
                     */
                    open_page_group : (CmsAdminC, page_group_uuid) => {
                        CmsAdminC.get_groups_and_pages(page_group_uuid)
                    },
                    /**
                     *
                     * @param Vue CmsAdminC
                     * @param string page_uuid
                     */
                    open_page : (CmsAdminC, page_uuid) => {
                        let AddLinkC = this.get_parent_component_by_name('AddLink')
                        AddLinkC.Link.link_class_name = 'GuzabaPlatform\\Cms\\Models\\Page'
                        AddLinkC.Link.link_object_uuid = page_uuid
                        AddLinkC.Link.link_name = CmsAdminC.get_page(page_uuid).page_name
                        CmsAdminC.highlighted_page_uuid = page_uuid
                    }
                }
            }
        },//end data()

    }
</script>

<style scoped>

</style>