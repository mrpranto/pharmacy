<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.users') }}</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled v-if="formState.disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('default.loading') }}
                </button>
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showAddForm" v-else>
                    <i class="mdi mdi-plus"></i>
                    {{ __('default.add_user') }}
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

        <AddNewUser :formState="formState" />
        <EditUser :formState="formState" />
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
                responseRole:[],
                validation: {},
                formData: {
                    role_id: '',
                    name: '',
                    email: null,
                    phone_number:'',
                    password:'',
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
                        title: 'roles',
                        type: 'custom-html',
                        key: 'role',
                        isVisible: true,
                        modifier: (role) => {
                            return role ? '<span class="badge badge-primary">'+role.name+'</span>' : ''
                        }
                    },
                    {
                        title: 'profile_picture',
                        type: 'custom-html',
                        key: 'profile_picture',
                        isVisible: true,
                        modifier: (row) => {
                            return row ? '<img src="'+row.full_url+'" class="img-thumbnail" style="width: 50px;height: 50px" alt="">' : '<img src="/images/avatar.png" class="img-thumbnail" style="width: 50px;height: 50px" alt="">'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'user-action-component',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: 10,
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
            await axios.get(url ?? '/get-users', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
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
        async getRoles(){
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
        getEditData(user){
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
                    this.deleteUser(id)
                }
            })
        },
        async deleteUser(id){
            await axios.delete(`/users/${id}`)
                .then(response => {
                    if (response.data.success) {
                        this.getData()
                        this.showSuccessMessage(response.data.success)
                    } else {
                        this.showErrorMessage(response.data.error)
                    }
                })
                .catch(err => {
                    this.showErrorMessage(err.data.error)
                })
        },
        showSuccessMessage(message) {
            notification['success']({
                message: 'Success',
                description: message,
            });
        },
        showErrorMessage(message) {
            notification['error']({
                message: 'Error',
                description: message,
            });
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
