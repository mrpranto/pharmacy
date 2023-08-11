<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.roles') }} <app-table-counter-component :total="options.total"/></h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled v-if="formState.disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('default.loading') }}
                </button>
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showAddForm" v-else>
                    <i class="mdi mdi-plus"></i>
                    {{ __('default.add_role') }}
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

        <AddNewRole :formState="formState"/>
        <EditRole :formState="formState" />
    </div>
</template>

<script>
import AddNewRole from "./AddNewRole.vue";
import EditRole from "./EditRole.vue";
import {notification} from "ant-design-vue";

export default {
    name: 'RoleList',
    components: {EditRole, AddNewRole},
    props: ['permission'],
    data() {
        return {
            formState: {
                openCreateRole: false,
                openEditRole: false,
                disabled: false,
                responsePermissions:[],
                current_id: '',
                selectAll: false,
                formData: {
                    name: '',
                    description: '',
                    isDeleteAble: true,
                    permissions: []
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
                        isVisible: false
                    },
                    {
                        title: 'name',
                        type: 'text',
                        key: 'name',
                        isVisible: true
                    },
                    {
                        title: 'description',
                        type: 'component',
                        componentName: 'role-description-component',
                        key: 'description',
                        isVisible: true
                    },
                    {
                        title: 'permissions',
                        type: 'component',
                        componentName: 'role-permission-component',
                        key: 'permissions',
                        isVisible: true
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'role-action-component',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
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
            await axios.get(url ?? '/get-roles', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getPermissions(){
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
            this.getPermissions()
            this.formState.formData = {
                name: '',
                description: '',
                isDeleteAble: true,
                permissions: [],
            }
            this.formState.selectAll = false;
            this.formState.openCreateRole = true;
        },
        onClose() {
            this.formState.openCreateRole = false;
        },
        getEditData(role){
            this.getPermissions()
            this.formState.current_id = role.id,
            this.formState.formData = {
                name: role.name,
                description: role.description,
                isDeleteAble: role.is_delete_able ? true : false,
                permissions: role.permissions.map(item => item.id),
            }
            this.formState.selectAll = false;
            this.formState.openEditRole = true;
        },
        onEditClose() {
            this.formState.openEditRole = false;
        },
        showDeleteForm(id){
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
        async deleteRole(id){
            await axios.delete(`/roles/${id}`)
                .then(response => {
                    if (response.data.success) {
                        this.getData()
                        this.$showSuccessMessage(response.data.success)
                    } else {
                        this.$showErrorMessage(response.data.error)
                    }
                })
                .catch(err => {
                    this.$showErrorMessage(err.data.error)
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
