<template>
    <div>
        <span class="badge badge-pill badge-dark cursor-pointer" @click="showModal" v-if="JSON.parse(value).length">
            <i class="mdi mdi-office-building"></i> {{ __('default.companies') }}
        </span>
        <a-modal v-model:open="open" :title="__('default.companies')" :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">
            <template v-for="(company) in JSON.parse(value)">
                <span class="badge badge-pill badge-primary m-1"> {{ company.name }}</span>
            </template>
        </a-modal>
    </div>
</template>
<script>
export default {
    name: "SupplierCompany",
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
