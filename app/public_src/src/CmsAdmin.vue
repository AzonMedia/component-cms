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
                <div v-if="error_message" class="error-message">
                    {{ error_message }}
                </div>
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
                page_group_uuid: '',
                error_message: '',
                page_groups: [],
                pages: [],
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
            get_groups_and_pages(page_group_uuid) {
                console.log(page_group_uuid)
                this.$http.get('/admin/cms/' + page_group_uuid )
                    .then(resp => {
                        console.log(resp);
                        this.page_groups = resp.data.page_groups;
                        /*
                        if (typeof resp.data.files !== "undefined") {
                            //this will not work - assigning and then setting the property
                            //the property first needs to be set on all records and then assigned to Files as otherwise the File.vue template will fail
                            //self.Files = Object.values(resp.data.files);
                            //this.unhighlight_all_files();
                            let Files = Object.values(resp.data.files);
                            for (const el in Files) {
                                Files[el].is_highlighted = 0;
                            }
                            self.Files = Files;
                        } else {
                            //console.log('No Files data received');
                            //self.show_toast('No Files data was received.');
                            this.error_message = 'No Files data was received.';
                        }
                         */

                    })
                    .catch(err => {
                        //console.log(err);
                        //self.show_toast(err.response.data.message);
                        this.error_message = err.response.data.message;
                        //self.Files = [];
                        //self.requestError = err;
                        //self.items_permissions = [];
                    }).finally(function(){
                        //self.$bvModal.show('class-permissions');
                    });
            }
        },
        mounted() {
            this.get_groups_and_pages(this.page_group_uuid);
        }
    }
</script>

<style scoped>
    .error-message {
        border: 2px solid red;
    }
</style>