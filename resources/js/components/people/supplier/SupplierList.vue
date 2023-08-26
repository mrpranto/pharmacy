<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.suppliers') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled
                                        v-if="formState.disabled">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    {{ __('default.loading') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                        @click.prevent="showAddForm"
                                        v-else>
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_supplier') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row inbox-wrapper">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <app-table-component :options="options"></app-table-component>
                    </div>
                </div>
            </div>
        </div>

        <AddNewSupplier :formState="formState"/>
        <EditSupplier :formState="formState"/>

    </div>
</template>

<script>
import AddNewSupplier from "./AddNewSupplier.vue";
import EditSupplier from "./EditSupplier.vue";

export default {
    name: 'SupplierList',
    components: {EditSupplier, AddNewSupplier},
    props: ['permission'],
    data() {
        return {
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                responseCompanies: [],
                current_id: '',
                selectAll: false,
                formData: {
                    name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    companies: [],
                },
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                },
                validation: {}
            },
            options: {
                loader: false,
                responseData: {},
                total: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        isVisible: false,
                        width: '5',
                    },
                    {
                        title: 'name',
                        type: 'text',
                        key: 'name',
                        orderAble: true,
                        isVisible: true,
                        width: '15',
                    },
                    {
                        title: 'phone_number',
                        type: 'text',
                        key: 'phone_number',
                        orderAble: true,
                        isVisible: true,
                        width: '15',
                    },
                    {
                        title: 'email',
                        type: 'text',
                        key: 'email',
                        orderAble: true,
                        isVisible: true,
                        width: '20',
                    },
                    {
                        title: 'address',
                        type: 'text',
                        key: 'address',
                        orderAble: true,
                        isVisible: true,
                        width: '20',
                    },
                    {
                        title: 'companies',
                        type: 'component',
                        componentName: 'supplier-company-component',
                        key: 'companies',
                        orderAble: false,
                        isVisible: true,
                        width: '15',
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'supplier-action-component',
                        isVisible: true,
                        width: '10',
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc'
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
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/peoples/get-suppliers', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getDependency() {
            this.formState.disabled = true
            await axios.get('/peoples/get-dependency')
                .then(response => {
                    this.formState.responseCompanies = response.data.companies
                    this.formState.disabled = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.getDependency()
            this.formState.formData = {
                name: '',
                phone_number: '',
                email: '',
                address: '',
                companies: [],
            }
            this.formState.validation = false;
            this.formState.openCreate = true;
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(supplier) {
            this.getDependency()
            this.formState.current_id = supplier.id;
            this.formState.formData = {
                name: supplier.name,
                phone_number: supplier.phone_number,
                email: supplier.email,
                address: supplier.address,
                companies: JSON.parse(supplier.companies).map(item => {
                    return item.id
                }),
            }
            this.formState.validation = {};
            this.formState.openEdit = true;
        },
        onEditClose() {
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
            await axios.delete(`/peoples/suppliers/${id}`)
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

<style>
.bg-gray {
    background-color: #f2f4f9;
}

.btn-gray {
    border: 1px #ededed solid;
    height: 34px;
}

.btn-gray:hover {
    background-color: #838282;
    color: #FFFFFF;
}
</style>
