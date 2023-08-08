<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.categories') }} <app-table-counter-component :total="options.total"/></h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled v-if="formState.disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('default.loading') }}
                </button>
                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" @click.prevent="showAddForm" v-else>
                    <i class="mdi mdi-plus"></i>
                    {{ __('default.add_new_category') }}
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

        <AddNewCategory :formState="formState"/>
        <EditCategory :formState="formState" />

    </div>
</template>
<script>
import AddNewCategory from "./AddNewCategory.vue";
import EditCategory from "./EditCategory.vue";

export default {
    name: "CategoryList",
    components: {EditCategory, AddNewCategory},
    props: ['permission'],
    data() {
        return {
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                formData: {
                    name: '',
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
                        type: 'custom-html',
                        key: 'description',
                        isVisible: true,
                        modifier: (description) => {
                            if (description?.length > 100) {
                                return description?.substring(0, 100) + ' ...';
                            }else {
                                return description
                            }
                        }
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        modifier: (status) => {
                            return  status === 1 ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'category-action-component',
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
    methods:{
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/product/get-categories', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.formState.formData = {
                name: '',
                description: '',
                status: true,
            }
            this.formState.validation = {};
            this.formState.openCreate = true;
        },
        onClose(){
            this.formState.openCreate = false;
        },
        getEditData(row) {
            this.formState.formData = {
                name: row.name,
                description: row.description,
                status: row.status === 1 ? true : false,
            }
            this.formState.current_id = row.id;
            this.formState.validation = {};
            this.formState.openEdit = true;
        },
        onEditClose(){
            this.formState.openEdit = false;
        },
    }
}
</script>
<style scoped>

</style>
