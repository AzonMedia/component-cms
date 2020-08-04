<template>
    <!--
    :cancel-disabled="action_state"
    :ok-disabled="loading_state"
    :ok-only="action_state && !loading_state"
    -->
    <b-modal
            id="delete-element-modal"
            :title="DeleteElement.modal_title"
            header-bg-variant="danger"
            header-text-variant="light"
            body-bg-variant="light"
            body-text-variant="dark"
            ok-title="Delete"
            ok-variant="danger"
            centered
            @ok="modal_ok_handler"
            @cancel="modal_cancel_handler"
            @show="modal_show_handler"
            size="lg"
    >
        <p>Please confirm you would like to delete <strong>{{DeleteElement.type}} <span v-for="(page_group_name, page_group_uuid) in $parent.PageGroupPath">/{{page_group_name}}</span>/{{DeleteElement.name}}</strong></p>
    </b-modal>

</template>

<script>
    export default {
        name: "Delete",
        props: {
            DeleteElement : Object
        },
        methods: {
            modal_ok_handler(bvModalEvent) {
                let url = this.DeleteElement.url;
                this.$http.delete(url).
                    then(function() {
                        //do nothing - in the finally it will reload the pages & groups
                    }).catch( err => {
                        //this.$parent.show_toast(err.response.data.message);
                        this.$parent.show_toast(err);
                    }).finally( () => {
                        this.$parent.get_groups_and_pages(this.$parent.page_group_uuid);
                    });
            },
            modal_cancel_handler(bvModalEvent) {
            },
            modal_show_handler(bvModalEvent) {
            }
        }
    }
</script>

<style scoped>

</style>