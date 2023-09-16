<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-3 mb-md-0">
                    {{ __('default.roles') }}
                    <app-table-counter-component :total="options.total"/>
                </h5>
            </div>
            <div>
                <a-spin v-if="loader"/>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled
                        v-if="formState.disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('default.loading') }}
                </button>
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showAddForm"
                        v-else>
                    <i class="mdi mdi-plus"></i>
                    {{ __('default.add_role') }}
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
                        <app-table-component :options="options" ref="appTable"></app-table-component>
                    </div>
                </div>
            </div>
        </div>

        <AddNewRole :formState="formState"/>
        <EditRole :formState="formState"/>
        <RoleDetails :show="show" />
    </div>
</template>

<script>
import AddNewRole from "./AddNewRole.vue";
import EditRole from "./EditRole.vue";
import RoleDetails from "./RoleDetails.vue";

export default {
    name: 'RoleList',
    components: {RoleDetails, EditRole, AddNewRole},
    props: ['permission'],
    data() {
        return {
            loader: false,
            formState: {
                openCreateRole: false,
                openEditRole: false,
                disabled: false,
                responsePermissions: [],
                current_id: '',
                list_path: '',
                current_list_url: '',
                selectAll: false,
                formData: {
                    name: '',
                    description: '',
                    isDeleteAble: true,
                    permissions: [],
                    module_ids: [],
                },
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
                        width: '20',
                    },
                    {
                        title: 'description',
                        type: 'component',
                        componentName: 'role-description-component',
                        key: 'description',
                        orderAble: true,
                        isVisible: true,
                        width: '40',
                    },
                    {
                        title: 'permissions',
                        type: 'component',
                        componentName: 'role-permission-component',
                        key: 'permissions',
                        isVisible: true,
                        width: '20',
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'role-action-component',
                        isVisible: true,
                        width: '15',
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc'
                },
                exportAble: {}
            },
            show:{
                role: {},
                open: false
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
            await axios.get(url ?? '/get-roles', {params: this.options.request})
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
            await axios.get('/roles/' + id)
                .then(response => {
                    this.show.role = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getPermissions() {
            this.formState.disabled = true
            await axios.get('/get-permissions')
                .then(response => {
                    this.formState.responsePermissions = response.data
                    this.formState.disabled = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.loader = true
            this.getPermissions()
            this.formState.formData = {
                name: '',
                description: '',
                isDeleteAble: true,
                permissions: [],
                module_ids: [],
            }
            this.formState.selectAll = false;
            this.formState.openCreateRole = true;
            this.loader = false
        },
        onClose() {
            this.formState.openCreateRole = false;
        },
        getEditData(role) {
            const role_permissions = role.permissions
            this.loader = true
            this.getPermissions()
            this.formState.current_id = role.id;
            this.formState.formData.name = role.name;
            this.formState.formData.description = role.description;
            this.formState.formData.isDeleteAble = role.is_delete_able ? true : false;
            this.formState.formData.permissions = role_permissions.map(item => item.id);
            const module_ids = role_permissions.map(item => item.module_id);
            const unique_module_ids = [...new Set(module_ids)];
            this.formState.formData.module_ids = unique_module_ids;
            this.formState.selectAll = false;
            this.formState.openEditRole = true;
            this.loader = false;
        },
        onEditClose() {
            this.formState.openEditRole = false;
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
                    this.deleteRole(id)
                }
            })
        },
        async deleteRole(id) {
            await axios.delete(`/roles/${id}`)
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
