<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.customers') }}
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
                                    {{ __('default.add_new_customer') }}
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

        <AddNewCustomer :formState="formState"/>
        <EditCustomer :formState="formState"/>
        <CustomerDetails :show="show"/>

    </div>
</template>

<script>
import AddNewCustomer from "./AddNewCustomer.vue";
import EditCustomer from "./EditCustomer.vue";
import CustomerDetails from "./CustomerDetails.vue";

export default {
    name: 'CustomerList',
    components: {CustomerDetails, EditCustomer, AddNewCustomer},
    props: ['permission'],
    data() {
        return {
            loader: false,
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                list_path: '',
                current_list_url: '',
                selectAll: false,
                formData: {
                    name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    status: true
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
                        key: 'sl',
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
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        orderAble: true,
                        width: '10',
                        modifier: (status) => {
                            return status === 1 ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'customer-action-component',
                        isVisible: true,
                        width: '15',
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    status: '',
                    order_dir: 'desc'
                },
                exportAble: {}
            },
            show:{
                customer:{},
                open:false
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
            await axios.get(url ?? '/peoples/get-customers', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.formState.list_path = response.data.path
                    this.formState.current_list_url = response.data.current_page
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/peoples/customers/' + id)
                .then(response => {
                    this.show.customer = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.formState.formData = {
                name: '',
                phone_number: '',
                email: '',
                address: '',
                status: true
            }
            this.formState.validation = false;
            this.formState.openCreate = true;
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(customer) {
            this.formState.current_id = customer.id;
            this.formState.formData = {
                name: customer.name,
                phone_number: customer.phone_number,
                email: customer.email,
                address: customer.address,
                status: customer.status === 1 ? true : false,
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
            await axios.delete(`/peoples/customers/${id}`)
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
        },
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
