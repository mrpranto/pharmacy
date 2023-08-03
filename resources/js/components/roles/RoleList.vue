<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Roles</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showDrawer">
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


        <a-drawer
            title="Create a new account"
            :width="720"
            :open="options.openCreateRole"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="onClose"
        >
            <a-form  layout="vertical">
                <a-row :gutter="16">
                    <a-col :span="12">
                        <a-form-item label="Name" name="name">
                            <a-input placeholder="Please enter user name" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="Url" name="url">
                            <a-input
                                style="width: 100%"
                                addon-before="http://"
                                addon-after=".com"
                                placeholder="please enter url"
                            />
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :span="12">
                        <a-form-item label="Owner" name="owner">
                            <a-select placeholder="Please a-s an owner">
                                <a-select-option value="xiao">Xiaoxiao Fu</a-select-option>
                                <a-select-option value="mao">Maomao Zhou</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="Type" name="type">
                            <a-select placeholder="Please choose the type">
                                <a-select-option value="private">Private</a-select-option>
                                <a-select-option value="public">Public</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :span="12">
                        <a-form-item label="Approver" name="approver">
                            <a-select placeholder="Please choose the approver">
                                <a-select-option value="jack">Jack Ma</a-select-option>
                                <a-select-option value="tom">Tom Liu</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="12">
                        <a-form-item label="DateTime" name="dateTime">
                            <a-date-picker
                                style="width: 100%"
                                :get-popup-container="trigger => trigger.parentElement"
                            />
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :span="24">
                        <a-form-item label="Description" name="description">
                            <a-textarea
                                :rows="4"
                                placeholder="please enter url description"
                            />
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
            <template #extra>
                <a-space>
                    <a-button @click="onClose">Cancel</a-button>
                    <a-button type="primary" @click="onClose">Submit</a-button>
                </a-space>
            </template>
        </a-drawer>

    </div>
</template>

<script>
export default {
    name: 'RoleList',
    props: ['name'],
    data() {
        return {
            options: {
                openCreateRole:false,
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
        },
        showDrawer(){
            this.options.openCreateRole = true;
        },
        onClose(){
            this.options.openCreateRole = false;
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
