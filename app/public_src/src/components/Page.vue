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
            @ok="proceed_action($event)"
            size="lg"
    >

    </b-modal>

</template>

<script>
    export default {
        name: "Page",
        props: {
            PageData : Object
        },
    }
</script>

<style scoped>

</style>