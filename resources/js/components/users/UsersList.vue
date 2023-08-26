<template>
    <div>

        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.users') }}
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
                                    {{ __('default.add_user') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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

        <AddNewUser :formState="formState"/>
        <EditUser :formState="formState"/>
    </div>
</template>

<script>

import {notification} from "ant-design-vue";
import AddNewUser from "./AddNewUser.vue";
import EditUser from "./EditUser.vue";

export default {
    name: 'UsersList',
    components: {EditUser, AddNewUser},
    props: ['permission'],
    data() {
        return {
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                responseRole: [],
                validation: {},
                formData: {
                    role_id: '',
                    name: '',
                    email: null,
                    phone_number: '',
                    password: '',
                    profile_picture: {}
                },
                layout: {
                    labelCol: {span: 5},
                    wrapperCol: {span: 19},
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
                        orderAble: false,
                        isVisible: false,
                        width: '5',
                    },
                    {
                        title: 'name',
                        type: 'component',
                        componentName: 'user-name-component',
                        rowValues: true,
                        key: 'name',
                        isVisible: true,
                        orderAble: true,
                        width: '25',
                    },
                    {
                        title: 'email',
                        type: 'text',
                        key: 'email',
                        orderAble: true,
                        isVisible: true,
                        width: '25',
                    },
                    {
                        title: 'phone_number',
                        type: 'text',
                        key: 'phone_number',
                        orderAble: true,
                        isVisible: true,
                        width: '20',
                    },
                    {
                        title: 'roles',
                        type: 'custom-html',
                        key: 'role',
                        orderAble: false,
                        isVisible: true,
                        modifier: (role) => {
                            return role ? '<span class="badge badge-primary">' + role.name + '</span>' : ''
                        },
                        width: '20',
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'user-action-component',
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
            await axios.get(url ?? '/get-users', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.formState.formData = {
                name: '',
                email: null,
                phone_number: '',
                password: '',
            }
            this.getRoles();
            this.formState.validation = {};
            this.formState.openCreate = true;
        },
        async getRoles() {
            await axios.get('/get-roles-for-users')
                .then(response => {
                    this.formState.responseRole = response.data
                })
                .catch(err => {
                    console.log(err)
                })
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(user) {
            this.getRoles()
            this.formState.current_id = user.id;
            this.formState.formData = {
                role_id: user.role_id,
                name: user.name,
                email: user.email,
                phone_number: user.phone_number,
                password: '',
                profile_picture: user?.profile_picture,
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
                    this.deleteUser(id)
                }
            })
        },
        async deleteUser(id) {
            await axios.delete(`/users/${id}`)
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
