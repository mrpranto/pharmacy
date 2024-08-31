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
                                        <h5>{{ __('default.edit_purchase') }}</h5>
                                    </div>
                                    <div>
                                        <a-spin v-if="loader"/>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                                        <a href="/purchases" :title="__('default.purchase_list')"
                                           class="btn btn-primary btn-icon-text"> <i
                                            class="mdi mdi-format-list-bulleted"></i> {{ __('default.purchase_list') }}
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
                        <div class="row pt-4">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.supplier')" required>
                                    <a-input-group compact
                                                   :class="formState.validation.supplier ? 'ant-input ant-input-status-error': ''">
                                        <a-select
                                            v-model:value="formState.formData.supplier"
                                            style="width: calc(100% - 45px)"
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
                                        <a-date-picker v-model:value="formState.formData.date" style="width: 100%"/>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.date">
                                        {{ formState.validation.date[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-3 col-lg-3">
                                <a-form-item :label="__('default.status')" required>
                                    <a-input-group compact :class="formState.validation.status ? 'ant-input ant-input-status-error': ''">
                                        <a-select v-model:value="formState.formData.status" style="width: 100%">
                                            <a-select-option value="received">{{ __('default.received') }}</a-select-option>
                                            <a-select-option value="pending">{{ __('default.pending') }}</a-select-option>
                                            <a-select-option value="canceled">{{ __('default.canceled') }}</a-select-option>
                                        </a-select>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.status">
                                        {{ formState.validation.status[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <a-form-item :label="__('default.products')" required>
                                    <a-input-group compact
                                                   :class="formState.validation.products ? 'ant-input ant-input-status-error': ''">
                                        <a-select
                                            v-model:value="searchProduct"
                                            style="width: calc(100% - 45px)"
                                            show-search
                                            :placeholder="__('default.search_product')"
                                            :options="formState.dependencies.products"
                                            :filter-option="false"
                                            @search="getProducts"
                                            @change="selectProduct"
                                        >
                                            <template
                                                #option="{ value: val, label, icon, company, category, unit, barcode, purchase_type }">
                                                <a-image :width="35" :height="35" :src="icon"
                                                         class="img-sm rounded-circle" :aria-label="val"/>
                                                &nbsp;&nbsp;
                                                <span>
													<b>{{ label }} ({{ barcode }})</b>, <b>{{
                                                        __('default.company')
                                                    }}:</b> <span class="text-muted">{{
                                                        company
                                                    }}</span>, <b>{{ __('default.category') }}:</b>
													<span class="text-muted">{{
                                                            category
                                                        }}</span>, <b>{{ __('default.unit') }}:</b> <span
                                                    class="text-muted">{{ unit }}</span>,
                                                     <b>{{ __('default.purchase_type') }}:</b> <span
                                                    class="text-muted">{{ purchase_type === '%' ? purchase_type : $currency_symbol }}</span>

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
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="2%">#</th>
                                                <th width="20%">{{ __('default.product') }}</th>
                                                <th width="8%" class="text-center">{{ __('default.mrp') }}
                                                    ({{ $currency_symbol }})
                                                </th>
                                                <th width="24%" class="text-center">{{ __('default.unit_price') }}
                                                    ({{ $currency_symbol }}/%)
                                                </th>
                                                <th width="24%" class="text-center">{{ __('default.sale_price') }}
                                                    ({{ $currency_symbol }}/%)
                                                </th>
                                                <th width="10%" class="text-center">{{ __('default.quantity') }}</th>

                                                <th width="10%" class="text-right">{{ __('default.sub_total') }}
                                                    ({{ $currency_symbol }})
                                                </th>
                                                <th width="2%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(product, product_index) in formState.formData.products"
                                                :key="product_index">
                                                <td width="2%" class="pl-2">{{ (product_index + 1) }}</td>
                                                <td width="20%">
                                                    <div class="d-flex align-items-center">
                                                        <i data-feather="corner-up-left" id="backToChatList"
                                                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                                        <figure class="mb-0 mr-2">
                                                            <a-image :width="35" :height="35"
                                                                     :src="product.product.photo"
                                                                     class="img-sm rounded-circle"
                                                                     :alt="product.product.name"/>
                                                            <div class="status online"></div>
                                                        </figure>
                                                        <div>
                                                            <p class="font-weight-bolder text-capital">
                                                                {{ product.product.name }} <small class="text-muted" :title="__('default.unit')">{{
                                                                    product.product.unit
                                                                }}</small></p>
                                                            <p class="text-muted tx-13"><b>{{
                                                                    __('default.barcode')
                                                                }}: </b>{{ product.product.barcode }}</p>
                                                            <p class="text-muted tx-13">
                                                                <span :title="__('default.company')">{{
                                                                        product.product.company
                                                                    }}</span>,
                                                                <br>
                                                                <span :title="__('default.category')">{{
                                                                        product.product.category
                                                                    }}</span>
                                                                <br>
                                                                <span :title="__('default.purchase_type')"
                                                                      class="badge badge-info"
                                                                      v-if="product.product.purchase_type === '%'">
                                                                    ({{ product.product.purchase_type }}) Percentage
                                                                </span>
                                                                <span :title="__('default.purchase_type')"
                                                                      class="badge badge-success"
                                                                      v-else>
                                                                    ({{ $currency_symbol }}) Direct Price
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td width="8%">
                                                    <a-input-number
                                                        v-if="product.product.purchase_type === '%'"
                                                        :class="product.stock_id ? 'readonly' : ''"
                                                        v-model:value="product.mrp"
                                                        :prefix="$currency_symbol"
                                                        type="number"
                                                        min="0"
                                                        @keyup="calculatePrices(product_index, 'mrp')"
                                                        @change="calculatePrices(product_index, 'mrp')"
                                                        style="width: 100%"
                                                    />
                                                    <div class="ant-form-item-explain-error text-danger"
                                                         v-if="formState.validation['products.'+product_index+'.mrp']">
                                                        {{
                                                            formState.validation['products.' + product_index + '.mrp'][0]
                                                        }}
                                                    </div>
                                                </td>
                                                <td width="24%">
                                                    <div>
                                                        <a-input-number
                                                            :class="product.stock_id ? 'readonly' : ''"
                                                            v-model:value="product.unit_price"
                                                            :prefix="$currency_symbol"
                                                            type="number"
                                                            min="0"
                                                            @keyup="calculateUnitPricePercentage(product_index, 'unit_price')"
                                                            @change="calculateUnitPricePercentage(product_index, 'unit_price')"
                                                            style="width: 55%"
                                                        />

                                                        <a-input-number
                                                            v-if="product.product.purchase_type === '%'"
                                                            :class="product.stock_id ? 'readonly' : ''"
                                                            v-model:value="product.unit_percentage"
                                                            :prefix="'%'"
                                                            type="number"
                                                            min="0"
                                                            @keyup="calculateUnitPrice(product_index, 'unit_percentage')"
                                                            @change="calculateUnitPrice(product_index, 'unit_percentage')"
                                                            style="width: 45%"
                                                        />
                                                    </div>

                                                    <div class="ant-form-item-explain-error text-danger"
                                                         v-if="formState.validation['products.'+product_index+'.unit_price']">
                                                        {{
                                                            formState.validation['products.' + product_index + '.unit_price'][0]
                                                        }}
                                                    </div>
                                                </td>
                                                <td width="24%">
                                                    <div>
                                                        <a-input-number
                                                            :class="product.stock_id ? 'readonly' : ''"
                                                            v-model:value="product.sale_price"
                                                            :prefix="$currency_symbol"
                                                            min="0"
                                                            @keyup="calculateSalePercentage(product_index, 'sale_price')"
                                                            @change="calculateSalePercentage(product_index, 'sale_price')"
                                                            style="width: 55%"
                                                        />

                                                        <a-input-number
                                                            v-if="product.product.purchase_type === '%'"
                                                            :class="product.stock_id ? 'readonly' : ''"
                                                            v-model:value="product.sale_percentage"
                                                            :prefix="'%'"
                                                            min="0"
                                                            @keyup="calculateSalePrice(product_index, 'sale_percentage')"
                                                            @change="calculateSalePrice(product_index, 'sale_percentage')"
                                                            style="width: 45%"
                                                        />
                                                    </div>
                                                    <div class="ant-form-item-explain-error text-danger"
                                                         v-if="formState.validation['products.'+product_index+'.sale_price']">
                                                        {{
                                                            formState.validation['products.' + product_index + '.sale_price'][0]
                                                        }}
                                                    </div>
                                                </td>
                                                <td width="10%">
                                                    <a-input-number
                                                        v-model:value="product.quantity"
                                                        min="1"
                                                        @keyup="calculatePrices(product_index, 'quantity')"
                                                        @change="calculatePrices(product_index, 'quantity')" style="width: 100%"/>
                                                    <div class="ant-form-item-explain-error text-danger"
                                                         v-if="formState.validation['products.'+product_index+'.quantity']">
                                                        {{
                                                            formState.validation['products.' + product_index + '.quantity'][0]
                                                        }}
                                                    </div>
                                                </td>
                                                <td width="10%" class="text-center">
                                                    <b>{{ $showCurrency(product.subTotal) }} </b>
                                                    <div class="ant-form-item-explain-error text-danger"
                                                         v-if="formState.validation['products.'+product_index+'.subTotal']">
                                                        {{
                                                            formState.validation['products.' + product_index + '.subTotal'][0]
                                                        }}
                                                    </div>
                                                </td>
                                                <td width="2%" class="text-center">
                                                    <a-popconfirm placement="left" title="Are you sure ?" ok-text="Yes"
                                                                  cancel-text="No"
                                                                  @confirm="removeProduct(product_index)">
                                                        <template #icon></template>

                                                        <a-tooltip :title="__('default.remove')">
                                                            <CloseCircleOutlined class="color-danger"
                                                                                 :style="{fontSize: '20px'}"/>
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
                                            <h4 class="text-center text-muted">{{ __('default.total_items') }} :
                                                {{ formState.totalItems }}</h4>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <h4 class="text-center text-muted">{{ __('default.total_unit') }} :
                                                {{ formState.totalUnits }}</h4>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <h4 class="text-center text-muted">{{ __('default.total_price') }} :
                                                {{ $showCurrency(formState.totalPrice) }}</h4>
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
                                                   :class="formState.validation.reference ? 'ant-input ant-input-status-error': ''">
                                        <a-input v-model:value="formState.formData.reference"
                                                 :placeholder="__('default.purchase_reference')" style="width: 100%">
                                            <template #suffix>
                                                <a-tooltip title="Generate new reference" @click="generateReference">
                                                    <sync-outlined style="color: rgba(0, 0, 0, 0.45)"/>
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.reference">
                                        {{ formState.validation.reference[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.other_cost')+` (${$currency_symbol})`"
                                             :label-col="{span: 8}">
                                    <a-input-number v-model:value="formState.formData.otherCost"
                                                    :prefix="$currency_symbol" min="0" @keyup="calculateTotal"
                                                    @change="calculateTotal" style="width: 100%"/>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.other_cost">
                                        {{ formState.validation.other_cost[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="'(-) '+__('default.discount')+` (${$currency_symbol})`"
                                             :label-col="{span: 8}">
                                    <a-input-number v-model:value="formState.formData.discount"
                                                    :prefix="$currency_symbol" min="0" @keyup="calculateTotal"
                                                    @change="calculateTotal" style="width: 100%"/>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.discount">
                                        {{ formState.validation.discount[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <a-form-item :label="__('default.note')" :label-col="{span: 8}">
                                    <a-input-group :wrapper-col="{span: 16}" compact
                                                   :class="formState.validation.note ? 'ant-input ant-input-status-error': ''">
                                        <a-textarea v-model:value="formState.formData.note"
                                                    :rows="6"
                                                    :placeholder="__('default.note')" style="width: 100%"></a-textarea>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.note">
                                        {{ formState.validation.note[0] }}
                                    </div>
                                </a-form-item>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <button class="btn btn-primary float-right" @click.prevent="update"><i
                                    class="mdi mdi-check-all"></i> {{ __('default.update') }}
                                </button>
                                <button class="btn btn-warning float-right mr-2" @click.prevent="reset"><i
                                    class="mdi mdi-restore"></i> {{ __('default.reset') }}
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
                                <hr/>
                            </dt>
                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">{{ __('default.subtotal') }} :</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">
                                {{ $showCurrency(this.formState.formData.subtotal) }}
                            </dd>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">{{ __('default.other_cost') }} :</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">
                                {{ $showCurrency(this.formState.formData.otherCost) }}
                            </dd>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right">(-) {{ __('default.discount') }} :</dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right">
                                {{ $showCurrency(this.formState.formData.discount) }}
                            </dd>

                            <dt class="col-sm-12 text-center text-muted">
                                <hr/>
                            </dt>

                            <dt class="col-sm-12 col-md-6 col-lg-6 text-right"><h4>{{ __('default.total') }}:</h4></dt>
                            <dd class="col-sm-12 col-md-4 col-lg-4 text-right"><h4>
                                {{ $showCurrency(formState.formData.total) }}</h4></dd>
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

import {
    DeleteOutlined,
    CloseCircleOutlined,
    QuestionCircleFilled,
    FormOutlined,
    SyncOutlined
} from "@ant-design/icons-vue";
import {message} from 'ant-design-vue';
import dayjs from 'dayjs';
import AddNewSupplier from "../people/supplier/AddNewSupplier.vue";
import AddNewProduct from "../products/products/AddNewProduct.vue";

export default {
    name: "EditPurchase",
    props: ['id'],
    components: {
        SyncOutlined,
        AddNewProduct,
        AddNewSupplier,
        DeleteOutlined,
        CloseCircleOutlined,
        QuestionCircleFilled,
        FormOutlined
    },
    data() {
        return {
            loader: false,
            product_price: 100,
            searchProduct: null,

            supplierFormState: {
                responseCompanies: [],
                openCreate: false,
                formData: {
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
            productFormState: {
                formData: {
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
                validation: {},
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                }
            },


            formState: {
                totalItems: 0,
                totalUnits: 0,
                totalPrice: 0,
                dependencies: {
                    suppliers: [],
                    products: []
                },
                formData: {
                    supplier: null,
                    date: dayjs(this.$today, 'YYYY-MM-DD'),
                    status: null,
                    products: [],
                    reference: null,
                    subtotal: 0,
                    otherCost: 0,
                    discount: 0,
                    total: 0,
                    note: null
                },
                validation: {}
            },
        }
    },
    created() {
        this.getEditData()
        this.getProducts()
        this.getSuppliers()
        this.generateReference()
    },
    mounted() {

    },
    methods: {
        getEditData() {
            this.loader = true
            axios.get(`/purchases/${this.id}`)
                .then(response => {
                    const responseData = response.data;
                    this.formState.formData.supplier = responseData.supplier_id
                    this.formState.formData.date = dayjs(responseData.date, 'YYYY-MM-DD')
                    this.formState.formData.status = responseData.status
                    this.formState.formData.products = this.getEditProducts(responseData.purchase_products)
                    this.formState.formData.discount = responseData.discount
                    this.formState.formData.discount = responseData.discount
                    this.formState.formData.subtotal = responseData.subtotal
                    this.formState.formData.otherCost = responseData.otherCost
                    this.formState.formData.reference = responseData.reference
                    this.formState.formData.total = responseData.total
                    this.formState.formData.note = responseData.note
                    this.calculateTotal()
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        getEditProducts(responseProduct){
           return responseProduct.map(item => {
                const product = {
                    id: item.product.id,
                    name: item.product.name,
                    barcode: item.product.barcode,
                    company: item.product.company.name,
                    category: item.product.category?.name,
                    purchase_type: item.product.purchase_type,
                    unit: item.product.unit?.name ? item.product.unit?.name + `(${item.product.unit.pack_size})` : item.product.unit.pack_size,
                    photo: `${item.product.product_photo ? item.product.product_photo?.full_url : '/images/medicine.png'}`
                }
                item.purchase_product_id = item.id
                item.product = product;
                item.discountAllow = item.discountAllow === 1 ? true : false;
                return item
            })
        },
        async update() {
            await axios.put('/purchases/'+this.id, this.formState.formData)
                .then(response => {
                    if (response.data.success) {

                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)

                        setTimeout(function (){
                            window.location.href = '/purchases'
                        }, 1000)

                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.formState.validation = err.response.data.errors
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                    }
                })
        },
        reset() {
            this.formState.formData = {
                supplier: null,
                date: dayjs(this.$today, 'YYYY-MM-DD'),
                status: null,
                products: [],
                reference: null,
                subtotal: 0,
                otherCost: 0,
                discount: 0,
                total: 0,
                note: null,
            }
            this.generateReference()
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
                            category: item.category?.name,
                            purchase_type: item.purchase_type,
                            unit: item.unit?.name ? item.unit?.name + `(${item.unit.pack_size})` : item.unit.pack_size
                        }
                    })
                    this.formState.dependencies.products = searchProduct
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getSuppliers() {
            await axios.get('/get-purchases-suppliers')
                .then(response => {
                    this.formState.dependencies.suppliers = response.data.map(item => {
                        return {
                            label: item.name + ` (${item.phone_number})`,
                            value: item.id
                        }
                    })
                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectProduct() {
            const selectedProduct = this.formState.dependencies.products.find(item => item.value === this.searchProduct)
            const productInfo = {
                id: selectedProduct.value,
                name: selectedProduct.label,
                barcode: selectedProduct.barcode,
                company: selectedProduct.company,
                category: selectedProduct.category,
                unit: selectedProduct.unit,
                photo: selectedProduct.icon,
                purchase_type: selectedProduct.purchase_type
            };

            const isExistProduct = this.formState.formData.products.find(item => item.product.id === productInfo.id);
            if (isExistProduct) {
                this.formState.formData.products = this.formState.formData.products.map(item => {
                    if (item.product.id === isExistProduct.product.id) {
                        item.quantity = item.quantity + 1
                        return item;
                    } else {
                        return item;
                    }
                })
                message.success(isExistProduct.product.name + ' already added in list.');
            } else {
                this.formState.formData.products.push({
                    product: productInfo,
                    mrp: null,
                    unit_price: null,
                    unit_percentage: null,
                    sale_price: null,
                    sale_percentage: null,
                    quantity: 1,
                    subTotal: 0
                })
            }
            this.calculateTotal();
            this.searchProduct = null
        },
        removeProduct(key) {
            const removeProductName = this.formState.formData.products[key].product.name;
            message.warning(removeProductName + ' remove from this list.');
            this.formState.formData.products.splice(key, 1);
            this.calculateTotal()
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        changeDiscountType(key, type) {
            this.formState.formData.products[key].discount_type = (type === '%' ? '%' : this.$currency_symbol)
            this.calculatePrices(key)
        },
        changeDiscountAllowed(key) {
            this.formState.formData.products[key].discountAllow = true
        },
        calculatePrices(key, column = null) {
            if (this.formState.validation[`products.${key}.${column}`]){
                this.formState.validation[`products.${key}.${column}`] = ''
            }

            const mrp = this.formState.formData.products[key].mrp;
            const unitPrice = this.formState.formData.products[key].unit_price;
            const unit_percentage = this.formState.formData.products[key].unit_percentage;
            const sale_price = this.formState.formData.products[key].sale_price;
            const sale_percentage = this.formState.formData.products[key].sale_percentage;
            const quantity = this.formState.formData.products[key].quantity;

            if (mrp && unitPrice){
                let cal_unit_percentage = (((mrp - unitPrice)/ mrp) * 100);
                this.formState.formData.products[key].unit_percentage = parseFloat(cal_unit_percentage).toFixed(2);
            }

            if (mrp && sale_price){
                let cal_sale_percentage = (((mrp - sale_price)/ mrp) * 100);
                this.formState.formData.products[key].sale_percentage = parseFloat(cal_sale_percentage).toFixed(2);
            }

            const subtotal = parseFloat(quantity * unitPrice)
            this.formState.formData.products[key].subTotal = subtotal.toFixed(2)
            this.calculateTotal();
        },

        calculateUnitPricePercentage(key, column = null){
            const mrp = this.formState.formData.products[key].mrp ?? 0;
            const unitPrice = this.formState.formData.products[key].unit_price ?? 0;
            if (mrp > 0){
                let cal_unit_percentage = (((mrp - unitPrice)/ mrp) * 100);
                this.formState.formData.products[key].unit_percentage = parseInt(unitPrice) > 0 ? parseFloat(cal_unit_percentage).toFixed(2) : null;
            }
            this.calculatePrices(key, column)
        },
        calculateUnitPrice(key, column = null){
            const mrp = this.formState.formData.products[key].mrp ?? 0;
            const unit_percentage = this.formState.formData.products[key].unit_percentage ?? 0;
            if (mrp > 0){
                let cal_unit_price = (mrp - ((mrp * unit_percentage) / 100));
                this.formState.formData.products[key].unit_price = parseInt(unit_percentage) > 0 ? parseFloat(cal_unit_price).toFixed(2) : null;
            }
            this.calculatePrices(key, column)
        },
        calculateSalePercentage(key, column = null){
            const mrp = this.formState.formData.products[key].mrp ?? 0;
            const sale_price = this.formState.formData.products[key].sale_price ?? 0;
            if (mrp > 0){
                let cal_sale_percentage = (((mrp - sale_price)/ mrp) * 100);
                this.formState.formData.products[key].sale_percentage = parseInt(sale_price) > 0 ? parseFloat(cal_sale_percentage).toFixed(2) : null;
            }
            this.calculatePrices(key, column)
        },
        calculateSalePrice(key, column = null){
            const mrp = this.formState.formData.products[key].mrp ?? 0;
            const sale_percentage = this.formState.formData.products[key].sale_percentage ?? 0;
            if (mrp > 0){
                let cal_sale_price = (mrp - ((mrp * sale_percentage) / 100));
                this.formState.formData.products[key].sale_price = parseInt(sale_percentage) > 0 ? parseFloat(cal_sale_price).toFixed(2) : null;
            }
            this.calculatePrices(key, column)
        },


        calculateTotal() {
            const selectedProducts = this.formState.formData.products
            this.formState.totalItems = selectedProducts.length;
            this.formState.totalUnits = selectedProducts.reduce((accumulator, currentitem) => {
                return parseFloat(accumulator) + parseFloat(currentitem.quantity ?? 0)
            }, 0);
            this.formState.totalPrice = selectedProducts.reduce((accumulator, currentitem) => {
                return parseFloat(accumulator) + parseFloat(currentitem.subTotal ?? 0)
            }, 0).toFixed(2);
            this.formState.formData.subtotal = this.formState.totalPrice;
            const otherCost = this.formState.formData.otherCost;
            const discount = this.formState.formData.discount;
            this.formState.formData.total = parseFloat((parseFloat(otherCost) + parseFloat(this.formState.totalPrice)) - parseFloat(discount)).toFixed(2)
        },
        generateReference() {
            this.formState.formData.reference = Math.floor(10000000000000 + Math.random() * 90000000000000)
        },
        getData() {
            this.getSuppliers()
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
        onClose() {
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
        showAddNewProductModal() {
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
                            label: item.name ? item.name + ` (${item.pack_size})` : item.pack_size
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
