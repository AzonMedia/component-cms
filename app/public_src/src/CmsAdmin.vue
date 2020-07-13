<template>
    <div class="crud">
        <div class="content">
            <!--
            <div id="data" class="tab">
            -->
            <div id="data">
                <h3>
                    Groups and Pages
                    <b-button variant="success" @click="new_page()" size="sm">New Page</b-button>
                    <b-button variant="success" @click="new_group()" size="sm">New Page Group</b-button>
                </h3>
            </div>
        </div>

        <!-- modals -->
        <PageC v-bind:PageData="PageData"></PageC>
        <PageGroupC v-bind:PageGroupData="PageGroupData"></PageGroupC>
    </div>


</template>

<script>

    import PageC from '@GuzabaPlatform.Cms/components/Page.vue'
    import PageGroupC from '@GuzabaPlatform.Cms/components/PageGroup.vue'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        name: "CmsAdmin",
        mixins: [
            ToastMixin,
        ],
        components: {
            PageC,
            PageGroupC,
        },
        data() {
            return {
                PageData: {
                    modal_title: '',
                    button_title: '',
                },
                PageGroupData: {
                    modal_title: '',
                    button_title: '',
                },
                //modal_variant: '',
                //button_variant: '',
                //action_state: false,
                //loading_state: false,
                //load_component: '',

            }
        },
        methods: {
            new_page() {
                this.modal_title = 'Create page';
                //this.modal_variant = 'success';
                //this.button_variant = 'success';
                //this.actionTitle = 'Create page';
                this.button_title = 'Create';
                //this.load_component = 'page';
                this.$bvModal.show('page-modal');
            },
            new_group() {
                this.PageGroupData.modal_title = 'Create page group';
                //this.modal_variant = 'success';
                //this.button_variant = 'success';
                //this.actionTitle = 'Create page group';
                this.PageGroupData.button_title = 'Create';
                //this.load_component = 'group';
                this.$bvModal.show('page-group-modal');
            },
            proceed_action() {

            },
            get_groups_and_pages() {

            }
        },
        mounted() {
            this.get_groups_and_pages();
        }
    }
</script>

<style scoped>

</style>