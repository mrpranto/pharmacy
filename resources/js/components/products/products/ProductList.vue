<template>
    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.products') }}
                    <app-table-counter-component :total="options.total"/>
                </h4>
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
                    {{ __('default.add_new_product') }}
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

        <AddNewProduct :formState="formState"/>

    </div>
</template>
<script>

import AddNewProduct from "./AddNewProduct.vue";

export default {
    name: "ProductList",
    components: {AddNewProduct},
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
                    barcode: '',
                    category: '',
                    company: '',
                    unit: '',
                    description: '',
                    status: true,
                },
                dependencies:{
                    categories:[],
                    companies:[],
                    units:[],
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
                        type: 'component',
                        componentName: 'product-name-component',
                        rowValues: true,
                        key: 'name',
                        isVisible: true,
                        orderAble: true,
                    },
                    {
                        title: 'category',
                        type: 'object',
                        key: 'category',
                        isVisible: true,
                        orderAble: true,
                        modifier: (category) => {
                            return category?.name;
                        }
                    },
                    {
                        title: 'company',
                        type: 'object',
                        key: 'company',
                        isVisible: true,
                        orderAble: true,
                        modifier: (company) => {
                            return company?.name;
                        }
                    },
                    {
                        title: 'unit',
                        type: 'object',
                        key: 'unit',
                        isVisible: true,
                        orderAble: true,
                        modifier: (unit) => {
                            return unit?.name + `(${unit?.pack_size})`;
                        }
                    },
                    {
                        title: 'description',
                        type: 'custom-html',
                        key: 'description',
                        isVisible: true,
                        orderAble: true,
                        modifier: (description) => {
                            if (description?.length > 50) {
                                return description?.substring(0, 50) + ' ...';
                            } else {
                                return description
                            }
                        }
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        orderAble: true,
                        modifier: (status) => {
                            return status === 1 ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        permission: this.permission,
                        componentName: 'unit-action-component',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    status: '',
                    order_by: 'id',
                    order_dir: 'desc',
                    category: '',
                    company: '',
                    unit: '',
                },
                exportAble: {},
                filters: [
                    {
                        title: 'category',
                        type: "drop-down-filter",
                        key: "category",
                        filterValue: 'category',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'company',
                        type: "drop-down-filter",
                        key: "company",
                        filterValue: 'company',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'unit',
                        type: "drop-down-filter",
                        key: "unit",
                        filterValue: 'unit',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                ],
            },
        }
    },
    created() {
        this.getData()
        this.getDependency()
    },
    mounted() {
    },
    watch:{
        'options.request.category': function (){
            this.getData()
        },
        'options.request.company': function (){
            this.getData()
        },
        'options.request.unit': function (){
            this.getData()
        }
    },
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/product/get-products', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getDependency(callFrom) {
            await axios.get('/product/get-dependency')
                .then(response => {
                    this.options.filters[0].option = response.data.categories.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    this.options.filters[1].option = response.data.companies.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    this.options.filters[2].option = response.data.units.map(item => {
                        return {
                            value: item.id,
                            label: item.name + ` (${item.pack_size})`
                        }
                    });

                    this.formState.dependencies.categories = this.options.filters[0].option;
                    this.formState.dependencies.companies = this.options.filters[1].option;
                    this.formState.dependencies.units = this.options.filters[2].option;

                    if (callFrom === 'addCategory'){
                        this.formState.formData.category = this.options.filters[0].option[0].value
                    }else if(callFrom === 'addCompany'){
                        this.formState.formData.company = this.options.filters[1].option[0].value
                    }else if(callFrom === 'addUnit'){
                        this.formState.formData.unit = this.options.filters[2].option[0].value
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        filterData(filterValue, filterType){
            if (filterType === 'category'){
                this.options.request.category = filterValue
            }else if(filterType === 'company'){
                this.options.request.company = filterValue
            }else if(filterType === 'unit'){
                this.options.request.unit = filterValue
            }
        },
        showAddForm() {
            this.formState.disabled = true;
            this.formState.formData = {
                name: '',
                barcode: '',
                description: '',
                image: '',
                status: true,
            }
            this.generateBarcode()
            this.formState.validation = {};
            this.formState.openCreate = true;
            this.formState.disabled = false;
        },
        generateBarcode(){
            this.formState.formData.barcode = Math.floor(100000000000 + Math.random() * 900000000000)
        },
        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(row) {
            this.formState.formData = {
                name: row.name,
                pack_size: row.pack_size,
                description: row.description,
                status: row.status === 1 ? true : false,
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
