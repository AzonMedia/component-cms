<template>
    <div class="crud">
        <div class="content">
            <!--
            <div id="data" class="tab">
            -->
            <div id="data">
                <h3>
                    Groups and Pages

                    <span>/<span class="page-group-path-element" @click="open_page_group('')">Home</span></span>
                    <!--
                    <span v-for="(PathElement, index) in page_group_path_arr">/<a href="#" @click="get_groups_and_pages(PathElement.page_group_uuid)">{{PathElement.page_group_name}}</a></span>
                    -->
                    <span v-for="(page_group_name, page_group_uuid) in PageGroupPath">/<span class="page-group-path-element" @click="open_page_group(page_group_uuid)">{{page_group_name}}</span></span>

                    <b-button variant="success" @click="new_page()" size="sm">New Page</b-button>
                    <b-button variant="success" @click="new_group()" size="sm">New Page Group</b-button>
                </h3>
                <div class="page-group-path">

                </div>
                <div v-if="error_message" class="error-message">
                    {{ error_message }}
                </div>


                <PageGroupC v-for="(PageGroupData, index) in page_groups" v-bind:PageGroupData="PageGroupData" v-bind:key="PageGroupData.meta_object_uuid" />
                <PageC v-for="(PageData, index) in pages" v-bind:PageData="PageGData" v-bind:key="PageData.meta_object_uuid" />

                <!--
                <b-table striped show-empty :items="items" :fields="fields" empty-text="No records found!" @row-clicked="row_click_handler" no-local-sorting @sort-changed="sortingChanged" head-variant="dark" table-hover>
                </b-table>
                -->

                <div v-if="!page_groups.length && !pages.length">
                    There are no page groups or pages.
                </div>
            </div>
        </div>

        <!-- modals -->
        <EditPageC v-bind:PageData="PageData"></EditPageC>
        <EditPageGroupC v-bind:PageGroupData="PageGroupData"></EditPageGroupC>
    </div>


</template>

<script>

    import PageC from '@GuzabaPlatform.Cms/components/Page.vue'
    import PageGroupC from '@GuzabaPlatform.Cms/components/PageGroup.vue'
    import EditPageC from '@GuzabaPlatform.Cms/components/EditPage.vue'
    import EditPageGroupC from '@GuzabaPlatform.Cms/components/EditPageGroup.vue'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        name: "CmsAdmin",
        mixins: [
            ToastMixin,
        ],
        components: {
            PageC,
            PageGroupC,
            EditPageC,
            EditPageGroupC,
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
                PageGroupPath: [],
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
            open_page_group(page_group_uuid) {
                if (page_group_uuid) {
                    this.$router.push('/admin/cms/' + page_group_uuid);
                } else {
                    this.$router.push('/admin/cms');
                }

            },
            get_groups_and_pages(page_group_uuid) {
                console.log(page_group_uuid);
                this.page_group_uuid = page_group_uuid;
                this.$http.get('/admin/cms/' + page_group_uuid )
                    .then(resp => {
                        console.log(resp);
                        this.page_groups = resp.data.page_groups;
                        this.pages = resp.data.pages;
                        this.PageGroupPath = resp.data.page_group_path;
                    })
                    .catch(err => {
                        //self.show_toast(err.response.data.message);
                        this.error_message = err.response.data.message;
                    }).finally(function(){
                    });
            }
        },
        watch: {
            $route (to, from) { // needed because by default no class is loaded and when it is loaded the component for the two routes is the same.
                let page_group_uuid = '';
                if (typeof this.$route.params.page_group_uuid !== "undefined") {
                    page_group_uuid = this.$route.params.page_group_uuid
                }
                this.get_groups_and_pages(page_group_uuid)
            }
        },
        mounted() {
            let page_group_uuid = this.page_group_uuid;
            if (typeof this.$route.params.page_group_uuid !== "undefined") {
                page_group_uuid = this.$route.params.page_group_uuid
            }
            this.get_groups_and_pages(page_group_uuid);
        }
    }
</script>

<style scoped>
    .error-message {
        border: 2px solid red;
    }
    .page-group-path-element {
        cursor: pointer;
        text-decoration: underline;
    }
</style>