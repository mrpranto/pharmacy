<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5>{{ __('default.attributes') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <button class="btn btn-primary btn-icon-text" type="button" disabled
                                        v-if="formState.disabled">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    {{ __('default.loading') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-icon-text"
                                        @click.prevent="showAddForm" v-else>
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_new_attribute') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-check-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.active') }} {{ __('default.attribute') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.active_attributes }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-close-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.in_active') }} {{ __('default.attribute') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.in_active_attributes }}</h3>
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

        <AddNewAttribute :formState="formState"/>
        <AttributeDetails :show="show"/>
        <EditAttribute :formState="formState"/>

    </div>
</template>
<script>
import AddNewAttribute from "./AddNewAttribute.vue";
import AttributeDetails from "./AttributeDetails.vue";
import EditAttribute from "./EditAttribute.vue";

export default {
    name: "AttributeList",
    components: {EditAttribute, AttributeDetails, AddNewAttribute},
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
                formData: {
                    name: '',
                    details: [],
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
                active_attributes: 0,
                in_active_attributes: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        key: 'sl',
                        isVisible: false,
                        width: '5'
                    },
                    {
                        title: 'name',
                        type: 'text',
                        key: 'name',
                        isVisible: true,
                        orderAble: true,
                        width: '20'
                    },
                    {
                        title: 'details',
                        type: 'custom-html',
                        key: 'details',
                        isVisible: true,
                        orderAble: true,
                        modifier: (details) => {
                            let items = '';
                            details.forEach(item => {
                                items += `<span class="badge badge-success mr-1">${item.name}</span>`
                            })
                            return items;
                        },
                        width: '50'
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        orderAble: true,
                        modifier: (status) => {
                            return status == '1' ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        },
                        width: '10'
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'attribute-action-component',
                        isVisible: true,
                        width: '15',
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    status: '',
                    order_by: 'id',
                    order_dir: 'desc'
                },
                exportAble: {}
            },
            show:{
                open: false,
                category: {}
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
            await axios.get(url ?? '/product/get-attributes', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data.attributes;
                    this.options.total = response.data.attributes.total;
                    this.options.active_attributes = response.data.active_attributes;
                    this.options.in_active_attributes = response.data.in_active_attributes;
                    this.formState.list_path = response.data.attributes.path;
                    this.formState.current_list_url = response.data.attributes.current_page;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/product/attributes/' + id)
                .then(response => {
                    this.show.attribute = response.data
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
                details: [{
                    name: '',
                    note: '',
                }],
                status: true,
            }
            this.formState.validation = {};
            this.formState.openCreate = true;
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(row) {
            this.formState.formData = {
                name: row.name,
                details: row.details,
                status: row.status == '1' ? true : false,
            }
            if (row.details.length){
                this.formState.formData.details = row.details;
            }else {
                this.formState.formData.details = [{
                    name: '',
                    note: '',
                }];
            }

            this.formState.current_id = row.id;
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
            await axios.delete(`/product/attributes/${id}`)
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
