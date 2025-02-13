<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.products') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
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
                                        @click.prevent="showAddForm"
                                        v-else>
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_new_product') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-check-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.active') }} {{ __('default.product') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.active_products }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-close-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.in_active') }} {{ __('default.product') }}</p>
                                <h3 class="mt-2 font-weight-light">{{ options.in_active_products }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-currency-usd text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.direct_price') }} {{ __('default.product') }} ({{ $currency_symbol }})</p>
                                <h3 class="mt-2 font-weight-light">{{ options.direct_price_products }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-percent text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.percentage_price') }} {{ __('default.product') }} (%)</p>
                                <h3 class="mt-2 font-weight-light">{{ options.percentage_products }}</h3>
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

        <AddNewProduct :formState="formState"/>
        <EditProduct :formState="formState"/>
        <ProductDetails :show="show"/>
        <AddOpeningStockForm :show="show"/>

    </div>
</template>
<script>

import AddNewProduct from "./AddNewProduct.vue";
import EditProduct from "./EditProduct.vue";
import ProductDetails from "./ProductDetails.vue";
import AddOpeningStockForm from "./AddOpeningStockForm.vue";

export default {
    name: "ProductList",
    components: {ProductDetails, EditProduct, AddNewProduct, AddOpeningStockForm},
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
                    barcode: '',
                    category: '',
                    company: '',
                    unit: '',
                    description: '',
                    purchase_type: null,
                    status: true,
                    attributes: [],
                    attributeItems: [],
                },
                dependencies: {
                    categories: [],
                    companies: [],
                    units: [],
                    attributes: [],
                    attributeItems : []
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
                active_products: 0,
                in_active_products: 0,
                direct_price_products: 0,
                percentage_products: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        key: 'sl',
                        width: '5',
                        isVisible: false
                    },
                    {
                        title: 'name',
                        type: 'component',
                        componentName: 'product-name-component',
                        rowValues: true,
                        key: 'name',
                        isVisible: true,
                        width: '23',
                        orderAble: true,
                    },
                    /*{
                        title: 'category',
                        type: 'object',
                        key: 'category',
                        isVisible: true,
                        orderAble: true,
                        width: '10',
                        modifier: (category) => {
                            return category?.name;
                        }
                    },*/
                    {
                        title: 'company',
                        type: 'object',
                        key: 'company',
                        isVisible: true,
                        orderAble: true,
                        width: '15',
                        modifier: (company) => {
                            return company?.name;
                        }
                    },
                    {
                        title: 'unit_pack_size',
                        type: 'object',
                        key: 'unit',
                        isVisible: true,
                        orderAble: true,
                        width: '15',
                        modifier: (unit) => {
                            return unit?.name ? unit?.name + `(${unit?.pack_size})` : unit?.pack_size;
                        }
                    },
                    {
                        title: 'purchase_type',
                        type: 'custom-html',
                        key: 'purchase_type',
                        isVisible: true,
                        orderAble: false,
                        width: '10',
                        modifier: (purchase_type) => {
                            return purchase_type === '%' ? `<span class="badge badge-info">(${purchase_type}) Percentage</span>` :
                                (purchase_type === '$' ? `<span class="badge badge-success">(${this.$currency_symbol}) Direct Price</span>` : '')
                        }
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        orderAble: true,
                        width: '10',
                        modifier: (status) => {
                            return status == '1' ? '<span class="badge badge-primary">Active</span>' :
                                '<span class="badge badge-danger">In-active </span>'
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'product-action-component',
                        width: '12',
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
                    purchase_type: '',
                },
                exportAble: {
                    csv: '',
                    excel: '',
                    pdf: '',
                    print: ''
                },
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
                    {
                        title: 'purchase_type',
                        type: "button-checkbox",
                        key: "purchase_type",
                        filterValue: '',
                        option: [
                            '$', '%',
                        ],
                    },
                ],
            },
            show:{
                product:{},
                open:false,
                openAddOpeningStock:false,
            }
        }
    },
    created() {
        this.getData()
        this.getDependency()
    },
    mounted() {
    },
    watch: {
        'options.request.category': function () {
            this.getData()
        },
        'options.request.company': function () {
            this.getData()
        },
        'options.request.unit': function () {
            this.getData()
        },
        'options.request.purchase_type': function () {
            this.getData()
        }
    },
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/product/get-products', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data.products;
                    this.options.total = response.data.products.total;
                    this.formState.list_path = response.data.products.path;
                    this.formState.current_list_url = response.data.products.current_page;

                    this.options.active_products = response.data.active_products;
                    this.options.in_active_products = response.data.in_active_products;
                    this.options.direct_price_products = response.data.direct_price_products;
                    this.options.percentage_products = response.data.percentage_products;

                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/product/products/' + id)
                .then(response => {
                    this.show.product = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showAddOpeningStockForm(id) {
            this.loader = true
            await axios.get('/product/products/' + id)
                .then(response => {
                    this.show.product = response.data
                    this.show.openAddOpeningStock = true
                    this.loader = false
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
                            label: item.pack_size
                        }
                    });

                    this.formState.dependencies.categories = this.options.filters[0].option;
                    this.formState.dependencies.companies = this.options.filters[1].option;
                    this.formState.dependencies.units = this.options.filters[2].option;
                    this.formState.dependencies.attributes = response.data.attributes.map(item => {
                        return {
                            value: item.id,
                            label: item.name,
                            details: item.details
                        }
                    });

                    if (callFrom === 'addCategory') {
                        this.formState.formData.category = this.options.filters[0].option[0].value
                    } else if (callFrom === 'addCompany') {
                        this.formState.formData.company = this.options.filters[1].option[0].value
                    } else if (callFrom === 'addUnit') {
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
        filterData(filterValue, filterType) {
            if (filterType === 'category') {
                this.options.request.category = filterValue
            } else if (filterType === 'company') {
                this.options.request.company = filterValue
            } else if (filterType === 'unit') {
                this.options.request.unit = filterValue
            } else if (filterType === 'purchase_type') {
                this.options.request.purchase_type = filterValue
            }
        },
        async showAddForm() {
            this.formState.disabled = true;
            this.formState.formData = {
                name: '',
                barcode: '',
                description: '',
                image: '',
                status: true,
                attributes:[],
                attributeItems:[]
            }
            this.generateBarcode()
            this.formState.validation = {};

            await setTimeout(() => {
                this.formState.openCreate = true;
                this.formState.disabled = false;
            })
        },
        generateBarcode() {
            this.formState.formData.barcode = Math.floor(100000000000 + Math.random() * 900000000000)
        },
        onClose() {
            this.formState.openCreate = false;
        },
        async getEditData(row) {
            this.loader = true;

            const variantOrder = row.variant_order ? row.variant_order.split("/") : [];

            const selectedAttributes = this.makeGroupBy(row.attributes, 'key');

            let originalArray = Object.keys(selectedAttributes);
            let orderArray = variantOrder;

            originalArray.sort(function(a, b) {
                return orderArray.indexOf(a) - orderArray.indexOf(b);
            });

            let attributes = []
            originalArray.map(label => this.formState.dependencies.attributes.find(obj => {
                if (obj.label === label){
                    attributes.push(obj.value)
                }
            }));

            this.formState.formData = {
                name: row.name,
                barcode: row.barcode,
                description: row.description,
                category: row.category_id,
                company: row.company_id,
                unit: row.unit_id,
                image: row.product_photo?.full_url,
                status: row.status == '1' ? true : false,
                purchase_type: row.purchase_type,
                attributes: attributes,
                attributeItems: selectedAttributes
            }
            this.formState.current_id = row.id;
            this.formState.validation = {};

            await setTimeout(() => {
                this.formState.openEdit = true;
                this.loader = false;
            })
        },
        makeGroupBy(array, property) {
            return array.reduce((acc, obj) => {
                const key = obj[property];
                if (!acc[key]) {
                    acc[key] = [];
                }
                acc[key].push(obj.value);
                return acc;
            }, {});
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
            await axios.delete(`/product/products/${id}`)
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
