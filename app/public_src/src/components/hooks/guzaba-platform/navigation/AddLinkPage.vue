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
        data() {
            return {
                EmbeddedData: {
                    //embedded: true,//no need of this... just defining the object and passing it is enough for the check inside CmsAdmin
                }
            }
        },
        components: {
            CmsAdmin
        },
    }
</script>

<style scoped>

</style>