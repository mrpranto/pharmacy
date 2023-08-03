<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Roles</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="plus"></i>
                    Add New Role
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

    </div>
</template>

<script>
export default {
    name: 'RoleList',
    props: ['name'],
    data() {
        return {
            options: {
                loader: false,
                responseData: {},
                columns: [
                    {
                        title: 'Sl',
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
