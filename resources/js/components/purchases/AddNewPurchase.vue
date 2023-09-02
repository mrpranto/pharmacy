<template>
    <div>
        <div class="row mb-2">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div>
                                        <h5>{{ __('default.add_purchase') }}</h5>
                                    </div>
                                    <div>
                                        <a-spin v-if="loader"/>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                                        <a href="" :title="__('default.purchase_list')"
                                           class="btn btn-primary btn-icon-text">
                                            <i class="mdi mdi-format-list-bulleted"></i> {{
                                                __('default.purchase_list')
                                            }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-5 col-lg-5">
                                <a-form-item :label="__('default.supplier')" :label-col="{span: 5}" required>
                                    <a-input-group compact :wrapper-col="{span: 19}"
                                                   :class="formState.validation.supplier ? 'ant-input ant-input-status-error': ''">
                                        <a-select
                                            v-model:value="formState.formData.supplier" style="width: calc(100% - 45px)"
                                            show-search
                                            :placeholder="__('default.supplier')"
                                            :options="formState.dependencies.suppliers"
                                            :filter-option="selectFilterOption"
                                        ></a-select>
                                        <a-tooltip :title="__('default.add_supplier')" placement="top">
                                            <a-button @click="showAddNewSupplierModal">
                                                <i class="mdi mdi-plus"></i>
                                            </a-button>
                                        </a-tooltip>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.supplier">
                                        {{ formState.validation.supplier[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-3 col-lg-3">
                                <a-form-item :label="__('default.date')" required>
                                    <a-input-group compact
                                                   :class="formState.validation.date ? 'ant-input ant-input-status-error': ''">
                                        <a-date-picker
                                            v-model:value="formState.formData.date"
                                            style="width: 100%"/>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.date">
                                        {{ formState.validation.date[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <a-form-item :label="__('default.status')" required>
                                    <a-input-group compact
                                                   :class="formState.validation.status ? 'ant-input ant-input-status-error': ''">
                                        <a-radio-group v-model:value="formState.formData.status" button-style="solid"
                                                       style="width: 100%">
                                            <a-radio-button value="Received">{{ __('default.received') }}</a-radio-button>
                                            <a-radio-button value="Pending">{{ __('default.pending') }}</a-radio-button>
                                            <a-radio-button value="Canceled">{{ __('default.canceled') }}</a-radio-button>
                                        </a-radio-group>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.status">
                                        {{ formState.validation.status[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <a-form-item :label="__('default.products')" :label-col="{span: 2}" required>
                                    <a-input-group compact :wrapper-col="{span: 22}"
                                                   :class="formState.validation.products ? 'ant-input ant-input-status-error': ''">
                                        <a-select
                                            v-model:value="searchProduct" style="width: calc(100% - 45px)"
                                            show-search
                                            :placeholder="__('default.search_product')"
                                            :options="formState.dependencies.products"
                                            :filter-option="false"
                                            @search="getProducts"
                                            @change="selectProduct"
                                        >
                                            <template #option="{ value: val, label, icon, company, category, unit, barcode }">
                                                <a-image
                                                    :width="35"
                                                    :height="35"
                                                    :src="icon"
                                                    class="img-sm rounded-circle"
                                                    :aria-label="val"
                                                />
                                                &nbsp;&nbsp;
                                                <span><b>{{ label }} ({{ barcode }})</b>,
                                                    <b>{{ __('default.company') }}:</b> <span
                                                        class="text-muted">{{ company }}</span>,
                                                    <b>{{ __('default.category') }}:</b> <span
                                                        class="text-muted">{{ category }}</span>,
                                                    <b>{{ __('default.unit') }}:</b> <span class="text-muted">{{
                                                            unit
                                                        }}</span>
                                                </span>
                                            </template>
                                        </a-select>
                                        <a-tooltip :title="__('default.add_new_product')" placement="top">
                                            <a-button @click="showAddNewProductModal">
                                                <i class="mdi mdi-plus"></i>
                                            </a-button>
                                        </a-tooltip>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.products">
                                        {{ formState.validation.products[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <template v-if="formState.formData.products.length > 0">

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="20%">{{ __('default.product') }}</th>
                                                <th width="15%" class="text-center">{{ __('default.unit_price') }} ({{ $currency_symbol }})</th>
                                                <th width="15%" class="text-center">{{ __('default.sale_price') }} ({{ $currency_symbol }})</th>
                                                <th width="10%" class="text-center">{{ __('default.quantity') }}</th>
                                                <th width="15%" class="text-center">{{ __('default.discount') }} ({{ $currency_symbol }})</th>
                                                <th width="15%" class="text-right">{{ __('default.sub_total') }} ({{ $currency_symbol }})</th>
                                                <th width="5%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(product, product_index) in formState.formData.products" :key="product_index">
                                                <td width="5%">{{ (product_index+1) }}</td>
                                                <td width="20%">
                                                    <div class="d-flex align-items-center">
                                                        <i data-feather="corner-up-left" id="backToChatList"
                                                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                                        <figure class="mb-0 mr-2">
                                                            <a-image
                                                                :width="35"
                                                                :height="35"
                                                                :src="product.photo"
                                                                class="img-sm rounded-circle"
                                                                :alt="product.name"
                                                            />
                                                            <div class="status online"></div>
                                                        </figure>
                                                        <div>
                                                            <p class="font-weight-bolder text-capital">{{ product.name }}</p>
                                                            <p class="text-muted tx-13">
                                                                <b>{{ __('default.barcode') }}: </b>{{ product.barcode }}
                                                            </p>
                                                            <p class="text-muted tx-13">
                                                                {{ product.company }},
                                                                {{ product.category }},
                                                                {{ product.unit }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="15%">
                                                    <a-input-number v-model:value="product_price" :prefix="$currency_symbol"
                                                                    style="width: 100%"/>
                                                </td>
                                                <td width="15%">
                                                    <a-input-number v-model:value="product_price" :prefix="$currency_symbol"
                                                                    style="width: 100%"/>
                                                </td>
                                                <td width="10%">
                                                    <a-input-number v-model:value="product_price" style="width: 100%"/>
                                                </td>
                                                <td width="15%" class="text-center">
                                                    <a-space style="width: 130%">
                                                        <a-input-number v-model:value="product_price"
                                                                        :prefix="$currency_symbol" style="width: 100%"/>

                                                        <a-input-group compact style="width: 100%">
                                                            <a-button>{{ $currency_symbol }}</a-button>
                                                            <a-button type="primary">%</a-button>
                                                        </a-input-group>
                                                    </a-space>

                                                </td>
                                                <td width="15%" class="text-right">
                                                    {{ $showCurrency(122000.00) }}
                                                </td>
                                                <td width="5%" class="text-center">
                                                    <a-popconfirm placement="left"
                                                                  title="Are you sure ?"
                                                                  ok-text="Yes"
                                                                  cancel-text="No"
                                                                  @confirm="confirm">
                                                        <template #icon>

                                                        </template>

                                                        <a-tooltip :title="__('default.remove')">
                                                            <CloseCircleOutlined
                                                                class="color-danger"
                                                                :style="{fontSize: '20px', marginLeft: '6px'}"/>
                                                        </a-tooltip>

                                                    </a-popconfirm>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="row mt-4">
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <h4 class="text-center text-muted">{{ __('default.total_items') }}:</h4>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <h4 class="text-center text-muted">{{ __('default.total_unit') }}:</h4>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <h4 class="text-center text-muted">{{ __('default.total_price') }}:</h4>
                                        </div>
                                    </div>
                                </div>

                            </template>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-2" v-if="formState.formData.products.length > 0">
            <div class="col-sm-12 col-md-8 col-lg-8">
                <div class="card w-100 h-100 d-inline-block radius-20 pt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.purchase_reference')" :label-col="{span: 8}">
                                    <a-input-group compact :wrapper-col="{span: 16}"
                                                   :class="formState.validation.purchase_reference ? 'ant-input ant-input-status-error': ''">
                                        <a-input v-model:value="formState.formData.purchase_reference"
                                                 :placeholder="__('default.purchase_reference')"
                                                 style="width: 100%"></a-input>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.purchase_reference">
                                        {{ formState.validation.purchase_reference[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.other_cost')" :label-col="{span: 6}">
                                    <a-input-group compact :wrapper-col="{span: 18}"
                                                   :class="formState.validation.other_cost ? 'ant-input ant-input-status-error': ''">
                                        <a-input v-model:value="formState.formData.other_cost"
                                                 :placeholder="__('default.other_cost')" style="width: 100%"></a-input>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.other_cost">
                                        {{ formState.validation.other_cost[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.discount')" :label-col="{span: 8}">
                                    <a-input-group :wrapper-col="{span: 16}" compact
                                                   :class="formState.validation.discount ? 'ant-input ant-input-status-error': ''">
                                        <a-input v-model:value="formState.formData.discount"
                                                 :placeholder="__('default.discount')" style="width: 100%"></a-input>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.discount">
                                        {{ formState.validation.discount[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.note')" :label-col="{span: 6}">
                                    <a-input-group :wrapper-col="{span: 18}" compact
                                                   :class="formState.validation.note ? 'ant-input ant-input-status-error': ''">
                                        <a-textarea v-model:value="formState.formData.note"
                                                    :placeholder="__('default.note')" style="width: 100%"></a-textarea>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.note">
                                        {{ formState.validation.note[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <button class="btn btn-primary float-right">
                                    <i class="mdi mdi-content-save"></i> {{ __('default.save') }}
                                </button>
                                <button class="btn btn-warning float-right mr-2">
                                    <i class="mdi mdi-restore"></i> {{ __('default.reset') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="card w-100 h-100 d-inline-block radius-20">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-12 text-center text-muted">
                                <h4>{{ __('default.payment_summary') }}</h4>
                                <hr>
                            </dt>
                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">{{ __('default.subtotal') }}:</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">1222</dd>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">{{ __('default.other_cost') }}:</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">1222</dd>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">(-) {{ __('default.discount') }}:</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">1222</dd>

                            <dt class="col-sm-12 text-center text-muted">
                                <hr>
                            </dt>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right"><h4>{{ __('default.total') }}:</h4></dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right"><h4>1222</h4></dd>

                        </dl>
                    </div>
                </div>
            </div>
        </div>



        <AddNewSupplier :formState="supplierFormState"/>
        <AddNewProduct :formState="productFormState"/>

    </div>
</template>
<script>

import {DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined} from "@ant-design/icons-vue";
import dayjs from 'dayjs';
import AddNewSupplier from "../people/supplier/AddNewSupplier.vue";
import AddNewProduct from "../products/products/AddNewProduct.vue";

export default {
    name: "AddNewPurchase",
    components: {AddNewProduct, AddNewSupplier, DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined},
    props: ['permission'],
    data() {
        return {
            loader: false,
            product_price: 100,
            searchProduct: null,
            supplierFormState:{
                responseCompanies: [],
                openCreate: false,
                formData:{
                    name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    companies: [],
                },
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                },
                validation: {},
            },
            productFormState:{
                formData :{
                    name: '',
                    barcode: '',
                    description: '',
                    image: '',
                    status: true,
                },
                dependencies: {
                    categories: [],
                    companies: [],
                    units: [],
                },
                openCreate: false,
                validation : {},
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                }
            },
            formState: {
                dependencies: {
                    suppliers: [],
                    products: []
                },
                formData: {
                    supplier: null,
                    date: dayjs(this.$today, 'YYYY-MM-DD'),
                    status: 'Received',
                    products: []
                },
                validation: {}
            },
        }
    },
    created() {
        this.getProducts()
        this.getSuppliers()
    },
    mounted() {

    },
    methods: {
        confirm() {
            alert('hello')
        },
        async getProducts(value = '') {
            await axios.get('/get-purchases-products', {params: {search: value}})
                .then(response => {
                    const searchProduct = response.data.map(item => {
                        return {
                            label: item.name,
                            barcode: item.barcode,
                            value: item.id,
                            icon: `${item.product_photo ? item.product_photo?.full_url : '/images/medicine.png'}`,
                            company: item.company.name,
                            category: item.category.name,
                            unit: item.unit.name + `(${item.unit.pack_size})`
                        }
                    })
                    this.formState.dependencies.products = searchProduct
                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectProduct() {
            const selectedProduct = this.formState.dependencies.products.find(item => item.value === this.searchProduct)
            this.formState.formData.products.push({
                id: selectedProduct.value,
                name: selectedProduct.label,
                barcode: selectedProduct.barcode,
                company: selectedProduct.company,
                category: selectedProduct.category,
                unit: selectedProduct.unit,
                photo: selectedProduct.icon,
            })
            this.searchProduct = null
        },
        async getSuppliers(callFrom = ''){
            await axios.get('/get-purchases-suppliers')
                .then(response => {
                    this.formState.dependencies.suppliers = response.data.map(item => {
                        return {
                            label : item.name +` (${item.phone_number})`,
                            value: item.id
                        }
                    })
                    if (callFrom === 'addNew'){
                        this.formState.formData.supplier = this.formState.dependencies.suppliers[0].value
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },

        getData(){
            this.getSuppliers('addNew')
            this.getProducts()
        },

        /*
        * Supplier add section
        */
        showAddNewSupplierModal() {
                this.getSupplierDependency()
                this.supplierFormState.formData = {
                    name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    companies: [],
                };
                this.supplierFormState.validation = false;
                this.supplierFormState.openCreate = true;
        },
        onClose(){
            this.supplierFormState.openCreate = false;
            this.productFormState.openCreate = false;
        },
        async getSupplierDependency() {
            await axios.get('/peoples/get-dependency')
                .then(response => {
                    this.supplierFormState.responseCompanies = response.data.companies
                })
                .catch(err => {
                    console.error(err)
                })
        },

        /*
        * Product add section start.
        */
        showAddNewProductModal(){
            this.loader = true;
            this.productFormState.formData = {
                name: '',
                barcode: '',
                description: '',
                image: '',
                status: true,
            }
            this.generateBarcode()
            this.productFormState.validation = {};
            this.getDependency();
            this.productFormState.openCreate = true;
            this.loader = false;
        },
        generateBarcode() {
            this.productFormState.formData.barcode = Math.floor(100000000000 + Math.random() * 900000000000)
        },
        async getDependency(callFrom) {
            await axios.get('/product/get-dependency')
                .then(response => {
                    const categories = response.data.categories.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    const companies = response.data.companies.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    const units = response.data.units.map(item => {
                        return {
                            value: item.id,
                            label: item.name + ` (${item.pack_size})`
                        }
                    });

                    this.productFormState.dependencies.categories = categories;
                    this.productFormState.dependencies.companies = companies;
                    this.productFormState.dependencies.units = units;

                    if (callFrom === 'addCategory') {
                        this.productFormState.formData.category = categories[0].value
                    } else if (callFrom === 'addCompany') {
                        this.productFormState.formData.company = companies[0].value
                    } else if (callFrom === 'addUnit') {
                        this.productFormState.formData.unit = units[0].value
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        }
        /*
        * Product add section end.
        */

    }
}
</script>
<style scoped>
.table td {
    padding: 0.2rem 0.6rem;
}

.table {
    border: 1px solid #ededed;
}
</style>
