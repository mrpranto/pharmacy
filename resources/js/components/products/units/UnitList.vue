<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-3 mb-md-0">{{ __('default.units') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h4>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled
                                        v-if="formState.disabled">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    {{ __('default.loading') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                        @click.prevent="showAddForm" v-else>
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_new_unit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h1><i class="mdi mdi-check-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.active') }} {{ __('default.units') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.active_units }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                                <h1><i class="mdi mdi-close-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.in_active') }} {{ __('default.units') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.in_active_units }}</h3>
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

        <AddNewUnit :formState="formState"/>
        <EditUnit :formState="formState"/>
        <UnitDetails :show="show"/>

    </div>
</template>
<script>

import AddNewUnit from "./AddNewUnit.vue";
import EditUnit from "./EditUnit.vue";
import UnitDetails from "./UnitDetails.vue";

export default {
    name: "UnitList",
    components: {UnitDetails, EditUnit, AddNewUnit},
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
                    pack_size: '',
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
                active_units: 0,
                in_active_units: 0,
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
                        width: '15'
                    },
                    {
                        title: 'pack_size',
                        type: 'text',
                        key: 'pack_size',
                        isVisible: true,
                        orderAble: true,
                        width: '15'
                    },
                    {
                        title: 'description',
                        type: 'custom-html',
                        key: 'description',
                        isVisible: true,
                        orderAble: true,
                        modifier: (description) => {
                            if (description?.length > 100) {
                                return description?.substring(0, 100) + ' ...';
                            } else {
                                return description
                            }
                        },
                        width: '35'
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
                        width: '15'
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'unit-action-component',
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
                unit:{},
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
            await axios.get(url ?? '/product/get-units', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data.units;
                    this.options.total = response.data.units.total;
                    this.options.active_units = response.data.active_units;
                    this.options.in_active_units = response.data.in_active_units;
                    this.formState.list_path = response.data.units.path
                    this.formState.current_list_url = response.data.units.current_page
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/product/units/' + id)
                .then(response => {
                    this.show.unit = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.formState.disabled = true;
            this.formState.formData = {
                name: '',
                pack_size: '',
                description: '',
                status: true,
            }
            this.formState.validation = {};
            this.formState.openCreate = true;
            this.formState.disabled = false;
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(row) {
            this.formState.formData = {
                name: row.name,
                pack_size: row.pack_size,
                description: row.description,
                status: row.status == '1' ? true : false,
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
            await axios.delete(`/product/units/${id}`)
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
