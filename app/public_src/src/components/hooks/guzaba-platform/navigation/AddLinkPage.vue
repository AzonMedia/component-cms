<template>
    <b-tab title="To Page">
        <!-- <p>Page: <v-select v-model="Link.link_class_name" :options="ModelClasses"></v-select></p> -->
    </b-tab>
</template>

<script>

    import vSelect from 'vue-select'
    import 'vue-select/dist/vue-select.css'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'
    import BaseMixin from '@GuzabaPlatform.Platform/BaseMixin.js'

    export default {
        name: "AddLinkPage",
        // data() {
        //     return {
        //         Link : {}
        //     }
        // },
        mixins: [
            ToastMixin,
            BaseMixin,
        ],
        methods: {
            /**
             * Loads the pages from all pages groups
             */
            load_pages() {

            }
        },
        mounted() {

            // this.$parent.test_method = function () {
            //     alert('test 2')
            // }
            //this.$parent.test_method()
            //console.log(this.get_template_names())
            //let AddLink = this.get_parent_component_by_name('AddLink');
            //console.log(AddLink.$options.name)
            // let AddLink = this.get_parent_component_by_name('AddLink');
            // AddLink.test_method = function() { //it is possible to overrive the methods of the component where the hook is defined
            //     alert('test 2')
            // }
        }
    }
</script>

<style scoped>

</style>