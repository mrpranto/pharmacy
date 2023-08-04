<template>
    <div>
        <button type="button" class="btn btn-outline-secondary btn-xs" @click="showModal" v-if="value.length">
            <i class="mdi mdi-eye-check"></i> Show Details
        </button>
        <a-modal v-model:open="open" title="Permission Details" :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">
            <template v-for="(permission) in value">
                <span class="badge badge-pill badge-primary m-1"> <small>{{ permission.module.name }}</small> <i class="mdi mdi-arrow-right"></i> {{ permission.name }}</span>
            </template>
        </a-modal>
    </div>
</template>
<script>
export default {
    name: "PermissionComponent",
    props: ['value'],
    data() {
        return {
            open: false
        }
    },
    methods: {
        showModal() {
            this.open = true
        }
    }
}
</script>
<style scoped>

</style>
