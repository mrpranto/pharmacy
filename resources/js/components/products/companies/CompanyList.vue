<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.companies') }} <app-table-counter-component :total="options.total"/></h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled v-if="formState.disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('default.loading') }}
                </button>
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showAddForm" v-else>
                    <i class="mdi mdi-plus"></i>
                    {{ __('default.add_new_company') }}
                </button>
            </div>
        </div>

        <div class="row inbox-wrapper">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <app-table-component :options="options"></app-table-component>
                    </div>
                </div>
            </div>
        </div>

        <AddNewCompany :formState="formState" />
        <EditCompany :formState="formState" />

    </div>
</template>
<script>

import AddNewCompany from "./AddNewCompany.vue";
import EditCompany from "./EditCompany.vue";

export default {
    name: "CompanyList",
    components: {EditCompany, AddNewCompany},
    props: ['permission'],
    data() {
        return {
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                formData: {
                    name: '',
                    email: '',
                    phone_number: '',
                    description: '',
                    status: true,
                },
                validation: {},
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                }
            },
            options: {
                loader: false,
                responseData: {},
                total: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        isVisible: false
                    },
                    {
                        title: 'name',
                        type: 'text',
                        key: 'name',
                        isVisible: true
                    },
                    {
                        title: 'email',
                        type: 'text',
                        key: 'email',
                        isVisible: true
                    },
                    {
                        title: 'phone_number',
                        type: 'text',
                        key: 'phone_number',
                        isVisible: true
                    },
                    {
                        title: 'description',
                        type: 'custom-html',
                        key: 'description',
                        isVisible: true,
                        modifier: (description) => {
                            if (description?.length > 100) {
                                return description?.substring(0, 100) + ' ...';
                            }else {
                                return description
                            }
                        }
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        modifier: (status) => {
                            return  status === 1 ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'company-action-component',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    status: ''
                },
                exportAble: {}
            }
        }
    },
    created() {
        this.getData()
    },
    mounted() {

    },
    methods:{
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/product/get-companies', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.formState.formData = {
                name: '',
                email: '',
                phone_number: '',
                description: '',
                status: true,
            }
            this.formState.validation = {};
            this.formState.openCreate = true;
        },
        onClose(){
            this.formState.openCreate = false;
        },
        getEditData(row) {
            this.formState.formData = {
                name: row.name,
                email: row.email,
                phone_number: row.phone_number,
                description: row.description,
                status: row.status === 1 ? true : false,
            }
            this.formState.current_id = row.id;
            this.formState.validation = {};
            this.formState.openEdit = true;
        },
        onEditClose(){
            this.formState.openEdit = false;
        },
        showDeleteForm(id) {
            const swalWithBootstrapButtons = Swal.mixin()
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'question',
                width: 400,
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: ' <i class="mdi mdi-check"></i> Yes, delete it!',
                cancelButtonText: '<i class="mdi mdi-close"></i> No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    this.delete(id)
                }
            })
        },
        async delete(id) {
            await axios.delete(`/product/companies/${id}`)
                .then(response => {
                    if (response.data.success) {
                        this.getData()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    this.$showErrorMessage(err.data.error, this.$notification_position, this.$notification_sound)
                })
        }
    }
}
</script>
<style scoped>

</style>
