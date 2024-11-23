<template>
    <div>
        <span class="badge badge-pill badge-dark cursor-pointer" @click="showModal" v-if="companies.length">
            <i class="mdi mdi-office-building"></i> {{ __('default.companies') }}
        </span>
        <a-modal v-model:open="open" :title="__('default.companies')" :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">
            <template v-for="(company) in companies">
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
            open: false,
            companies: []
        }
    },
    methods: {
        showModal() {
            this.open = true
        }
    },
    mounted() {
        this.companies = JSON.parse(JSON.stringify(this.value))
    }
}
</script>
<style scoped>

</style>
