<template>
    <!--
    :cancel-disabled="action_state"
    :ok-disabled="loading_state"
    :ok-only="action_state && !loading_state"
    -->
    <b-modal
            id="page-group-modal"
            :title="PageGroupData.modal_title"
            header-bg-variant="success"
            header-text-variant="light"
            body-bg-variant="light"
            body-text-variant="dark"
            :ok-title="PageGroupData.button_title"
            ok-variant="success"
            centered
            @ok="modal_ok_handler"
            @cancel="modal_cancel_handler"
            @show="modal_show_handler"
            size="lg"
    >
        <p>Page Group name: <span v-for="(page_group_name, page_group_uuid) in $parent.PageGroupPath">/{{page_group_name}}</span>/ <input v-model="page_group_name" type="text" placeholder="page group name" /></p>
    </b-modal>

</template>

<script>
    export default {
        name: "EditPageGroup",
        props: {
            PageGroupData : Object
        },
        data() {
            return {
                page_group_name: '',
            };
        },
        methods: {
            modal_ok_handler(bvModalEvent) {
                let url = '/admin/cms/page-group';
                if (this.PageGroupData.page_group_uuid) {
                    url += '/' + this.PageGroupData.page_group_uuid;
                }
                let SendValues = {};
                SendValues.page_group_name = this.page_group_name;
                //SendValues.parent_page_group_uuid = null;
                SendValues.parent_page_group_uuid = this.$parent.page_group_uuid;
                this.$http(
                    {
                        method: this.PageGroupData.method,
                        url: url,
                        data: SendValues
                    }
                ).
                    then(function() {
                        //do nothing - in the finally it will reload the pages & groups
                    }).catch( err => {
                        this.$parent.show_toast(err.response.data.message);
                    }).finally( () => {
                        this.page_group_name = '';
                        this.$parent.get_groups_and_pages(this.$parent.page_group_uuid);
                    });
            },
            modal_cancel_handler(bvModalEvent) {
                this.page_group_name = '';
            },
            modal_show_handler(bvModalEvent) {
                this.page_group_name = '';
                if (this.PageGroupData.page_group_name) {
                    this.page_group_name = this.PageGroupData.page_group_name;
                }
            }
        }
    }
</script>

<style scoped>

</style>