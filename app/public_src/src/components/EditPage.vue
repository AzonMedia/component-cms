<template>
    <!--
    :cancel-disabled="action_state"
    :ok-disabled="loading_state"
    :ok-only="action_state && !loading_state"
    -->
    <b-modal
            id="page-modal"
            :title="PageData.modal_title"
            header-bg-variant="success"
            header-text-variant="light"
            body-bg-variant="light"
            body-text-variant="dark"
            :ok-title="PageData.button_title"
            ok-variant="success"
            centered
            @ok="modal_ok_handler"
            @cancel="modal_cancel_handler"
            @show="modal_show_handler"
            size="lg"
    >
        <p>Page name: <span v-for="(page_group_name, page_group_uuid) in $parent.PageGroupPath">/{{page_group_name}}</span>/ <input v-model="page_name" type="text" placeholder="page name" /></p>
        <p>Page slug: <input v-model="page_slug" type="text" placeholder="page-slug"></p>
        <p>Page content:

            <quill-editor
                    ref="quill_editor"
                    v-model="page_content"
                    :options="EditorOption"
                    @blur="on_editor_blur($event)"
                    @focus="on_editor_focus($event)"
                    @ready="on_editor_ready($event)"
            />
        </p>
    </b-modal>

</template>

<script>

    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import { quillEditor } from 'vue-quill-editor'

    export default {
        name: "EditPage",
        props: {
            PageData : Object
        },
        components: {
            quillEditor,
        },
        data() {
            return {
                page_name: '',
                page_slug: '',
                page_content: '',
                page_group_uuid: '',
                page_uuid: '',
                EditorOption: {},
            };
        },
        computed: {
            editor() {
                return this.$refs.quill_editor.quill
            }
        },
        methods: {
            modal_ok_handler(bvModalEvent) {

                // let url = '/admin/cms/page';
                // if (this.page_uuid) {
                //     url = '/admin/cms/page/' + this.page_uuid;
                // }
                let url = this.get_route('GuzabaPlatform\\Cms\\Models\\Page:crud_action_create')
                if (this.page_uuid) {
                    url = this.get_route('GuzabaPlatform\\Cms\\Models\\Page:crud_action_update', this.page_uuid)
                }
                let SendValues = {};
                SendValues.page_name = this.page_name;
                if (!this.page_group_uuid) {
                    this.page_group_uuid = this.$parent.page_group_uuid;
                }
                SendValues.page_group_uuid = this.page_group_uuid;
                SendValues.page_content = this.page_content
                SendValues.page_slug = this.page_slug
                let method = this.page_uuid ? 'put' : 'post';
                this.$http(
                    {
                        method: method,
                        url: url,
                        //data: this.$stringify(sendValues)
                        data: SendValues
                    }
                ).
                    then(function() {
                        //do nothing - in the finally it will reload the pages & groups
                    }).catch( err => {
                        this.$parent.show_toast(err.response.data.message);
                    }).finally( () => {
                        this.page_name = '';
                        this.page_slug = '';
                        this.page_content = '';
                        this.$parent.get_groups_and_pages(this.$parent.page_group_uuid);
                    });
            },
            modal_cancel_handler(bvModalEvent) {
                //this.page_name = '';
            },
            modal_show_handler(bvModalEvent) {
                //this.page_name = '';
                if (this.PageData.page_uuid) {
                    //this.$http.get('/admin/cms/page/' + this.PageData.page_uuid)
                    this.$http.get(this.get_route('GuzabaPlatform\\Cms\\Models\\Page:crud_action_read', this.PageData.page_uuid))
                        .then(resp => {
                            this.page_name = resp.data.page_name;
                            this.page_slug = resp.data.page_slug;
                            this.page_content = resp.data.page_content;
                            this.page_uuid = resp.data.meta_object_uuid;
                            this.page_group_uuid = resp.data.page_group_uuid;
                        })
                        .catch(err => {
                            this.$parent.show_toast(err);
                        });
                } else {
                    this.page_name = '';
                    this.page_slug = '';
                    this.page_content = '';
                    this.page_group_uuid = null;
                    this.page_uuid = '';
                }

            },
            on_editor_blur(quill) {

            },
            on_editor_focus(quill) {

            },
            on_editor_ready(quill) {

            },
        }
    }
</script>

<style scoped>

</style>