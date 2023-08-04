<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.roles') }}</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
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

    </div>
</template>

<script>
import AddNewRole from "./AddNewRole.vue";

export default {
    name: 'RoleList',
    components: {AddNewRole},
    props: ['name'],
    data() {
        return {
            formState: {
                openCreateRole: false,
                disabled: false,
                responsePermissions:[],
                formData: {
                    name: '',
                    description: '',
                    permissions: []
                },
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                },
                validateMessages: {
                    required: '${label} is required!',
                    types: {
                        email: '${label} is not a valid email!',
                        number: '${label} is not a valid number!',
                    },
                    number: {
                        range: '${label} must be between ${min} and ${max}',
                    },
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
                        title: 'Name',
                        type: 'text',
                        key: 'name',
                        isVisible: true
                    },
                    {
                        title: 'Description',
                        type: 'component',
                        componentName: 'role-description-component',
                        key: 'description',
                        isVisible: true
                    },
                    {
                        title: 'Permissions',
                        type: 'component',
                        componentName: 'role-permission-component',
                        key: 'permissions',
                        isVisible: true
                    },
                    {
                        title: 'Action',
                        type: 'action',
                        componentName: 'role-action-component',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: 10,
                    search: '',
                },
                exportAble: {
                    csv: 'http://127.0.0.1:8000/roles',
                    excel: 'http://127.0.0.1:8000/roles',
                    pdf: 'http://127.0.0.1:8000/roles',
                    print: 'http://127.0.0.1:8000/roles',
                }
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
            this.formState.openCreateRole = true;
        },
        onClose() {
            this.formState.openCreateRole = false;
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
