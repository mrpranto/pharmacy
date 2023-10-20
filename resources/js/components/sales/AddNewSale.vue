<template>
    <div class="row m-3">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="row mb-3">
                <div class="col-10">
                    <a-input size="large"
                             :placeholder="__('default.search_sale_product')"
                             v-model:value="formState.request.search">
                        <template #prefix>
                            <SearchOutlined/>
                        </template>
                        <template #suffix v-if="formState.request.search">
                            <a-tooltip class="text-muted" style="font-size: 10px" title="Clear"
                                       @click.prevent="clearSearch">
                                <i class="mdi mdi-close-circle h5 cursor-pointer"></i>
                            </a-tooltip>
                        </template>
                    </a-input>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary btn-block btn-lg" style="border-radius: 5px"
                            @click.prevent="formState.showFilterArea = true">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="feather feather-filter">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card" v-if="formState.showFilterArea">
                        <div class="card-body">
                            <div class="row border-bottom pb-2">
                                <div class="col-sm-6">
                                    <p class="text-muted h6">{{ __('default.product_filter') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <a class="text-primary float-right text-decoration-none cursor-pointer"
                                       @click.prevent="clearSearch">
                                        {{ __('default.clear_filter') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <a-form-item required>
                                        <a-input-group compact>
                                            <a-select
                                                style="width: 100%"
                                                show-search
                                                v-model:value="formState.request.category"
                                                :options="formState.dependencies.categories"
                                                :filter-option="selectFilterOption"
                                                :placeholder="__('default.category')">
                                            </a-select>
                                        </a-input-group>
                                        <a class="text-primary float-right text-decoration-none cursor-pointer"
                                           @click.prevent="formState.request.category = null">
                                            <small>{{ __('default.clear') }}</small>
                                        </a>
                                    </a-form-item>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <a-form-item required>
                                        <a-input-group compact>
                                            <a-select
                                                style="width: 100%"
                                                show-search
                                                v-model:value="formState.request.company"
                                                :options="formState.dependencies.companies"
                                                :filter-option="selectFilterOption"
                                                :placeholder="__('default.company')">
                                            </a-select>
                                        </a-input-group>
                                        <a class="text-primary float-right text-decoration-none cursor-pointer"
                                           @click.prevent="formState.request.company = null">
                                            <small>{{ __('default.clear') }}</small>
                                        </a>
                                    </a-form-item>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="formState.loader">
                <div class="col-12 text-center">
                    <a-spin/>
                </div>
            </div>
            <div v-else class="row mt-3 products-area mr-0"
                 :class="windowHeight > 620 ? 'products-area-height-700' : 'products-area-height-400'">
                <div class="col-sm-12 col-md-4 col-lg-4 mb-3 cursor-pointer"
                     v-for="(product, product_index) in this.formState.dependencies.products"
                     :key="product_index" @click.prevent="showProductDetails(product)">
                    <div class="card w-100 h-100 d-inline-block product-area">
                        <div class="card-body">
                            <div class="text-center mb-2">
                                <div class="badge badge-success btn-block" v-if="checkProductSelected(product.id)">
                                    <i class="mdi mdi-check-circle"></i> Already in cart.
                                </div>
                            </div>
                            <div class="media d-block d-sm-flex flex-column align-items-center">
                                <div class="d-flex align-items-center" style="height: 100px">
                                    <figure class="mb-0 mr-3">
                                        <img
                                            v-if="product.product_photo"
                                            :width="100"
                                            :src="product.product_photo.full_url"
                                            class="img-thumbnail rounded-circle"
                                            :alt="product.name"/>
                                        <img
                                            v-else
                                            :width="100"
                                            :src="'/images/medicine.png'"
                                            class="img-thumbnail rounded-circle"
                                            :alt="product.name"/>
                                        <div class="status online"></div>
                                    </figure>
                                </div>

                                <div class="media-body">
                                    <p class="font-weight-bolder mt-3 mb-1">{{ product.name.toUpperCase() }}</p>
                                    <p>
                                        <span class="font-weight-bold">{{
                                                __('default.barcode')
                                            }} : </span> {{ product.barcode }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.company')
                                            }} : </span>{{ product.company.name }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.category')
                                            }} : </span>{{ product.category.name }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.unit')
                                            }} : </span>{{ product.unit.name + `(${product.unit.pack_size})` }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.purchase_type')
                                            }} : </span> <span v-if="product.purchase_type === '%'"
                                                               class="badge badge-info">{{ product.purchase_type }} Percentage</span>
                                        <span v-else class="badge badge-success">{{
                                                $currency_symbol
                                            }} Direct Price</span><br>
                                        <span class="font-weight-bold">{{
                                                __('default.stock_type')
                                            }} : </span>{{ product.stocks.length }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.total_stock')
                                            }} : </span> <span class="badge badge-primary">{{
                                            totalStock(product.stocks)
                                        }}</span> <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="row">
                <div class="col-10">
                    <a-form-item required>
                        <a-input-group compact>
                            <a-select
                                v-model:value="formState.formData.customer"
                                style="width: 100%"
                                :size="'large'"
                                show-search
                                :placeholder="__('default.customers')"
                                :options="formState.dependencies.customers"
                                :filter-option="selectFilterOption"
                                @search="getCustomers"
                            >
                            </a-select>
                        </a-input-group>
                    </a-form-item>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary btn-lg btn-block" style="border-radius: 5px"
                            v-if="customerFormState.disabled">
                        <span class="spinner-border spinner-border-sm" role="status"
                              aria-hidden="true"></span>
                    </button>
                    <button class="btn btn-primary btn-lg btn-block" v-else style="border-radius: 5px"
                            @click.prevent="showCustomerAddForm">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="feather feather-user-plus">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="labels col-12 row border-bottom no-gutters py-2 mb-0">
                            <b class="col-4 text-muted text-center">{{ __('default.selected_product') }}</b>
                            <b class="col-2 text-muted text-center">{{ __('default.price') }}</b>
                            <b class="col-3 text-muted text-center">{{ __('default.quantity') }}</b>
                            <b class="col-2 text-muted text-center">{{ __('default.sub_total') }}</b>
                            <b class="col-1 text-muted text-right"></b>
                        </div>

                        <div class="col-12 cart-area text-muted"
                             :class="windowHeight > 620 ? 'cart-area-height-420' : 'cart-area-height-300'">
                            <template v-if="formState.formData.products.length"
                                      v-for="(cart, cart_index) in formState.formData.products">
                                <div class="row border-bottom py-2 mb-0">
                                    <div class="col-4 align-middle">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <svg v-if="cart.discountArea === false"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-down cursor-pointer"
                                                 @click.prevent="showHideDiscountArea(cart_index, true)">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>

                                            <svg v-else
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-up cursor-pointer"
                                                 @click.prevent="showHideDiscountArea(cart_index, false)">
                                                <polyline points="18 15 12 9 6 15"></polyline>
                                            </svg>


                                            <p class="ml-3">
                                                {{ cart.product.name }}
                                                <br>
                                                <small><span class="font-weight-bolder">{{
                                                        __('default.barcode')
                                                    }} :</span> {{ cart.product.barcode }},
                                                </small>
                                                <br>
                                                <small><span class="font-weight-bolder">{{
                                                        __('default.unit')
                                                    }} :</span> {{ cart.product.unit }},
                                                    <br>
                                                    <span class="badge badge-info" v-if="cart.product.purchase_type === '%'">{{ cart.product.purchase_type }} Percentage</span>
                                                    <span class="badge badge-success" v-else>{{ $currency_symbol }} Direct Price</span>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center pt-3">
                                        <span>{{ $showCurrency(cart.sale_price) }}</span>
                                    </div>
                                    <div class="col-3 text-center pt-3">

                                        <a-input-group compact>
                                            <a-button @click.prevent="incrementDecrement(cart_index, '-')" size="small"
                                                      style="z-index: 1">
                                                <i class="mdi mdi-minus"></i>
                                            </a-button>
                                            <a-input v-model:value="cart.quantity"
                                                     size="small"
                                                     type="number"
                                                     min="1"
                                                     @input="calculatePrice(cart_index)"
                                                     style="width: 60px;"/>
                                            <a-button @click.prevent="incrementDecrement(cart_index, '+')" size="small">
                                                <i class="mdi mdi-plus"></i>
                                            </a-button>
                                        </a-input-group>

                                    </div>
                                    <div class="col-2 text-center pt-3">
                                        <span>{{ $showCurrency(cart.sub_total) }}</span>
                                    </div>
                                    <div class="col-1 text-center pt-3">
                                        <a-popconfirm placement="left" title="Are you sure ?" ok-text="Yes"
                                                      cancel-text="No"
                                                      @confirm="removeFromCart(cart_index)">
                                            <template #icon></template>

                                            <a-tooltip :title="__('default.remove')">
                                                <i class="mdi mdi-close-circle h5 cursor-pointer color-danger"></i>
                                            </a-tooltip>
                                        </a-popconfirm>
                                    </div>

                                    <div class="col-12 pt-3" v-if="cart.discountArea === true">
                                        <div class="row">
                                            <div class="col-2">
                                                <a-form-item :label="__('default.mrp')">
                                                    <p>{{ $currency_symbol +' '+ cart.mrp }}</p>
                                                </a-form-item>
                                            </div>
                                            <div class="col-5">
                                                <a-form-item :label="__('default.sale_price')">
                                                    <a-input-number v-model:value="cart.sale_price"
                                                                    @change="calculatePrice(cart_index)"
                                                                    @blur="setOriginalPrice(cart_index)"
                                                                    :id="'sale_price_'+cart_index"
                                                                    style="width: 100%"
                                                                    :prefix="$currency_symbol"
                                                                    :min="1"
                                                                    :class="cart.showAlert ? 'ant-input ant-input-status-error': ''"
                                                                    size="small"/>
                                                    <div class="ant-form-item-explain-error" v-if="cart.showAlert">
                                                        {{ __('default.cant_lower_sale_price') }}
                                                    </div>
                                                </a-form-item>
                                            </div>
                                            <div class="col-5">
                                                <a-form-item :label="__('default.discount')">
                                                    <a-input-number v-if="cart.product.purchase_type === '$'"
                                                                    v-model:value="cart.sale_percentage"
                                                                    style="width: 100%"
                                                                    prefix="%"
                                                                    :id="'sale_percentage_'+cart_index"
                                                                    disabled
                                                                    :min="1"
                                                                    size="small"/>
                                                    <a-input-number v-else
                                                                    v-model:value="cart.sale_percentage"
                                                                    style="width: 100%"
                                                                    prefix="%"
                                                                    :id="'sale_percentage_'+cart_index"
                                                                    @change="calculatePrice(cart_index)"
                                                                    @blur="setOriginalPrice(cart_index)"
                                                                    :min="1"
                                                                    :class="cart.showAlert ? 'ant-input ant-input-status-error': ''"
                                                                    size="small"/>
                                                    <div class="ant-form-item-explain-error" v-if="cart.showAlert">
                                                        {{ __('default.cant_greater_sale_percentage') }}
                                                    </div>
                                                </a-form-item>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="row pt-5" style="margin-top: 50px">
                                    <div class="col-12 mt-5">
                                        <h1 class="text-center">
                                            <i class="mdi mdi-cart"></i>
                                        </h1>
                                        <h3 class="text-center">No items to show</h3>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="col-12 pt-4">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <h5>{{ __('default.total_items') }} : {{ formState.formData.totalUnit }}</h5>
                                </div>
                                <div class="col-4 text-center">
                                    <h5>{{ __('default.total_unit') }} : {{ formState.formData.totalUnitQuantity }}</h5>
                                </div>
                                <div class="col-4 text-center">
                                    <h5>{{ __('default.total_subtotal') }} :
                                        {{ $showCurrency(formState.formData.totalSubTotal) }}</h5>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="col-12">
                            <dl class="row mt-4">

                                <dt class="col-sm-12 col-md-6 col-lg-6 text-center">{{ __('default.subtotal') }}
                                    ({{ $currency_symbol }}) :
                                </dt>
                                <dd class="col-sm-12 col-md-4 col-lg-5 text-right h5 mb-3">
                                    {{ $showCurrency(formState.formData.totalSubTotal) }}
                                </dd>

                                <dt class="col-sm-12 col-md-6 col-lg-6 text-center">{{ __('default.other_cost') }}
                                    ({{ $currency_symbol }}) :
                                </dt>
                                <dd class="col-sm-12 col-md-4 col-lg-5">
                                    <input type="number"
                                           style="width: 200px"
                                           step="0.01"
                                           v-model="formState.formData.otherCost"
                                           @input="calculateTotal"
                                           class="form-control form-control-sm text-right float-right text-black rounded"
                                           :placeholder="__('default.other_cost') +' ('+ $currency_symbol +')'"
                                           autocomplete="off">
                                </dd>

                                <dt class="col-sm-12 col-md-6 col-lg-6 text-center">(-) {{ __('default.discount') }}
                                    ({{ $currency_symbol }}) :
                                </dt>
                                <dd class="col-sm-12 col-md-4 col-lg-5 text-right">
                                    <input type="number"
                                           style="width: 200px;"
                                           step="0.01"
                                           v-model="formState.formData.discount"
                                           @input="calculateTotal"
                                           class="form-control form-control-sm text-right float-right text-black"
                                           :placeholder="__('default.discount') +' ('+ $currency_symbol +')'"
                                           autocomplete="off">
                                </dd>

                                <dt class="col-sm-12 text-center text-muted">
                                    <hr/>
                                </dt>

                                <dt class="col-sm-12 col-md-6 col-lg-6 text-center"><h4>{{ __('default.total') }} :</h4>
                                </dt>
                                <dd class="col-sm-12 col-md-4 col-lg-5 text-right"><h5>
                                    {{ $showCurrency(formState.formData.grandTotal) }}</h5></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-4">
                    <button class="btn btn-primary btn-block"><i class="mdi mdi-eye"></i> Preview</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-warning btn-block"><i class="mdi mdi-pause"></i> Draft</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-success btn-block"><i class="mdi mdi-check-bold"></i> Save & Print</button>
                </div>
            </div>
        </div>

        <AddNewCustomer :formState="customerFormState"/>


        <a-modal v-model:open="formState.selectedProduct.openStock"
                 width="1000px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.stock_details')">
            <hr>
            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <div class="media d-block d-sm-flex">
                        <div class="d-flex align-items-center">
                            <figure class="mb-0 mr-3">
                                <a-image :width="80"
                                         :src="formState.selectedProduct.photo"
                                         class="img-sm img-thumbnail rounded-circle"
                                         :alt="formState.selectedProduct.name"/>
                            </figure>
                        </div>

                        <div class="media-body">
                            <h4 class="mt-0 mb-1">{{ formState.selectedProduct.name.toUpperCase() }}</h4>
                            <p>
                                <span class="font-weight-bolder">{{
                                        __('default.barcode')
                                    }} : </span>{{ formState.selectedProduct.barcode }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.company')
                                    }} : </span>{{ formState.selectedProduct.company }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.category')
                                    }} : </span>{{ formState.selectedProduct.category }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.unit')
                                    }} : </span>{{ formState.selectedProduct.unit }} <br>
                                <span class="font-weight-bolder">{{ __('default.purchase_type') }} : </span>
                                <span class="badge badge-info" v-if="formState.selectedProduct.purchase_type === '%'">
                                    ({{ formState.selectedProduct.purchase_type }}) Percentage
                                </span>
                                <span class="badge badge-success" v-else>
                                    ({{ $currency_symbol }}) Direct Price
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 pt-4">
                    <div class="row ">
                        <div class="col-sm-12 col-md-4 col-lg-4 mb-4"
                             v-for="(stock, stock_index) in formState.selectedProduct.stocks" :key="stock_index">
                            <a @click.prevent="selectStockProduct(stock, formState.selectedProduct.id)">
                                <div class="card border p-2 w-100 h-100 d-inline-block stock-card">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.mrp') }} : </span> <span
                                            class="font-weight-bolder">{{ $showCurrency(stock.mrp) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.sale_price') }} : </span> <span
                                            class="font-weight-bolder">{{ $showCurrency(stock.sale_price) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.sale_percentage') }} : </span> <span
                                            class="font-weight-bolder">{{ stock.sale_percentage }}%</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.purchase_quantity') }} : </span> <span
                                            class="font-weight-bolder">{{ stock.purchase_quantity }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.sale_quantity') }} : </span> <span
                                            class="font-weight-bolder">{{ stock.sale_quantity }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>{{ __('default.available_quantity') }} : </span> <span
                                            class="font-weight-bolder">{{ stock.available_quantity }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </a-modal>

    </div>
</template>

<script>
import {
    UserAddOutlined,
    DownloadOutlined,
    ShoppingCartOutlined,
    SearchOutlined,
    CloseOutlined, CloseCircleOutlined
} from "@ant-design/icons-vue";
import AddNewCustomer from "../people/customer/AddNewCustomer.vue";
import {message} from "ant-design-vue";

export default {
    name: "AddNewSale",
    components: {
        CloseCircleOutlined,
        AddNewCustomer,
        UserAddOutlined,
        DownloadOutlined,
        ShoppingCartOutlined,
        SearchOutlined,
        CloseOutlined
    },
    props: ['categories', 'companies', 'user_email'],
    data() {
        return {
            windowHeight: window.innerHeight,
            customerFormState: {
                callFrom: 'sale',
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                list_path: '',
                current_list_url: '',
                selectAll: false,
                formData: {
                    name: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    status: true
                },
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                },
                validation: {}
            },
            formState: {
                loader: false,
                showFilterArea: false,
                showDiscountArea: false,
                dependencies: {
                    products: [],
                    customers: [],
                    categories: [],
                    companies: [],
                },
                formData: {
                    customer: null,
                    customerName: null,
                    products: [],
                    totalUnit: 0,
                    totalUnitQuantity: 0,
                    totalSubTotal: 0,
                    otherCost: 0,
                    discount: 0,
                    grandTotal: 0
                },
                request: {
                    search: '',
                    category: null,
                    company: null
                },
                selectedProduct: {}
            }
        }
    },
    created() {
        this.getCartHistory()
        this.setData()
        this.getProduct()
    },
    watch: {
        'formState.formData.customer': function (){
            if (this.formState.formData.customer !== null){
                const dependencyCustomer = this.formState.dependencies.customers.find(item => item.value === this.formState.formData.customer);
                this.formState.formData.customerName = dependencyCustomer?.label;
                this.setCartHistory();
            }
        },
        'formState.request.search': function () {
            this.getProduct()
        },
        'formState.request.category': function () {
            this.getProduct()
        },
        'formState.request.company': function () {
            this.getProduct()
        },
        categories: {
            immediate: true,
            deep: true,
            handler() {
                if (this.categories) {
                    this.formState.dependencies.categories = this.categories.map(item => {
                        return {
                            label: item.name,
                            value: item.id
                        }
                    })
                }
            },
        },
        companies: {
            immediate: true,
            deep: true,
            handler() {
                if (this.companies) {
                    this.formState.dependencies.companies = this.companies.map(item => {
                        return {
                            label: item.name,
                            value: item.id
                        }
                    })
                }
            },
        }
    },
    mounted() {

    },
    methods: {
        selectStockProduct(stock, product_id) {
            const product = this.formState.dependencies.products.find(item => item.id === product_id);
            const existProduct = this.formState.formData.products.find(item => item.product.id === product_id && item.sale_price === stock.sale_price)
            if (existProduct) {
                this.formState.formData.products.map(item => {
                    if (item.product.id === existProduct.product.id && item.sale_price === stock.sale_price) {
                        item.quantity = item.quantity + 1
                        item.sub_total = (item.sale_price * item.quantity)
                        return item;
                    } else {
                        return item;
                    }
                })
                message.success(existProduct.product.name + ' already added in cart.');
            } else {
                this.formState.formData.products.push({
                    product: {
                        id: product.id,
                        name: product.name.toUpperCase(),
                        barcode: product.barcode,
                        unit: product.unit.name + `(${product.unit.pack_size})`,
                        purchase_type: product.purchase_type
                    },
                    type: product.purchase_type,
                    sale_price: stock.sale_price,
                    original_sale_price: stock.sale_price,
                    mrp: stock.mrp,
                    sale_percentage: stock.sale_percentage,
                    quantity: 1,
                    sub_total: stock.sale_price,
                    discountArea: false,
                    showAlert:false,
                })
            }
            this.formState.selectedProduct.openStock = false
            this.calculateTotal()
            this.setCartHistory();
        },
        calculatePrice(index) {
            const original_sale_price = this.formState.formData.products[index].original_sale_price === null ? 0 : this.formState.formData.products[index].original_sale_price;
            const mrp = this.formState.formData.products[index].mrp === null ? 0 : this.formState.formData.products[index].mrp;
            const salePrice = this.formState.formData.products[index].sale_price === null ? 0 : this.formState.formData.products[index].sale_price;
            const salePercentage = this.formState.formData.products[index].sale_percentage === null ? 0 : this.formState.formData.products[index].sale_percentage;

            if (event.target.id === 'sale_price_' + index) {
                this.formState.formData.products[index].sale_percentage = (((mrp - salePrice) / mrp) * 100).toFixed(2);
            } else if (event.target.id === 'sale_percentage_' + index) {
                this.formState.formData.products[index].sale_price = (mrp - ((mrp * salePercentage) / 100)).toFixed(2);
            }

            const newSalePrice = this.formState.formData.products[index].sale_price

            if (original_sale_price > newSalePrice) {
                message.error('You can\'t lower this sale price from original price.');
                this.formState.formData.products[index].showAlert = true
            }else {
                this.formState.formData.products[index].showAlert = false
            }
            const quantity = this.formState.formData.products[index].quantity === null ? 0 : this.formState.formData.products[index].quantity;

            if (quantity) {
                this.formState.formData.products[index].sub_total = (parseFloat(newSalePrice) * parseInt(quantity)).toFixed(2);
            }
            this.calculateTotal();
            this.setCartHistory();
        },
        setOriginalPrice(index) {
            const original_sale_price = this.formState.formData.products[index].original_sale_price === null ? 0 : this.formState.formData.products[index].original_sale_price;
            const salePrice = this.formState.formData.products[index].sale_price === null ? 0 : this.formState.formData.products[index].sale_price;
            if (original_sale_price > salePrice) {
                message.error('You can\'t lower this sale price from original price.');
                this.formState.formData.products[index].sale_price = original_sale_price;
            }
        },
        calculateTotal() {
            this.formState.formData.totalUnit = this.formState.formData.products.length;
            this.formState.formData.totalUnitQuantity = this.formState.formData.products.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseInt(object.quantity);
            }, 0);
            const totalSubTotal = this.formState.formData.products.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseFloat(object.sub_total);
            }, 0).toFixed(2);

            this.formState.formData.totalSubTotal = totalSubTotal

            const otherCost = parseFloat(this.formState.formData.otherCost === '' ? 0 : this.formState.formData.otherCost);
            const discount = parseFloat(this.formState.formData.discount === '' ? 0 : this.formState.formData.discount);
            this.formState.formData.grandTotal = ((parseFloat(totalSubTotal) + otherCost) - discount);
            this.setCartHistory();
        },
        incrementDecrement(index, type) {
            const quantity = parseInt(this.formState.formData.products[index].quantity);
            if (type === '-') {
                const changeQty = quantity - 1;
                if (changeQty > 0) {
                    this.formState.formData.products[index].quantity = changeQty;
                }
            } else if (type === '+') {
                this.formState.formData.products[index].quantity = quantity + 1;
            }
            this.calculatePrice(index)
            this.calculateTotal()
        },
        showHideDiscountArea(key, value){
            this.formState.formData.products[key].discountArea = value;
            this.setCartHistory();
        },
        removeFromCart(key) {
            const removeProductName = this.formState.formData.products[key].product.name;
            message.warning(removeProductName + ' remove from this list.');
            this.formState.formData.products.splice(key, 1);
            this.calculateTotal();
            this.setCartHistory();
        },
        showProductDetails(product) {
            this.formState.selectedProduct = {
                id: product.id,
                photo: product.product_photo ? product.product_photo.full_url : '/images/medicine.png',
                name: product.name,
                barcode: product.barcode,
                company: product.company.name,
                category: product.category.name,
                unit: product.unit.name + `(${product.unit.pack_size})`,
                purchase_type: product.purchase_type,
                stocks: product.stocks,
                openStock: true,
            };
        },
        async getProduct() {
            this.formState.loader = true;
            this.formState.dependencies.products = [];
            await axios.get('/get-sales-products', {params: this.formState.request})
                .then(response => {
                    this.formState.dependencies.products = response.data
                    this.formState.loader = false;
                })
                .catch(error => console.error(error))

        },
        checkProductSelected(product_id) {
            const existProduct = this.formState.formData.products.find(item => item.product.id === product_id);
            if (existProduct) {
                return true
            } else {
                return false
            }
        },
        totalStock(stocks) {
            return stocks.reduce((accumulator, object) => {
                return parseFloat(accumulator) + parseFloat(object.available_quantity);
            }, 0);
        },
        clearSearch() {
            this.formState.showFilterArea = false
            this.formState.request = {
                search: '',
                category: null,
                company: null
            }
        },
        async getCustomers(value = 'Walk-In') {
            if (value !== 'Walk-In'){
                value = value.split(' (')[0]
            }
            this.formState.dependencies.customers = [];
            await axios.get('/get-sales-customers', {params: {search: value}})
                .then(response => {
                    this.formState.dependencies.customers = response.data.map(item => {
                        return {
                            label: item.name + (item.phone_number ? ` (${item.phone_number})` : ''),
                            value: item.id
                        }
                    })

                    const firstCustomer = response.data.find(item => item.name === value || item.phone_number === value);

                    if (firstCustomer) {
                        this.formState.formData.customer = firstCustomer.id;
                        this.formState.formData.customerName = firstCustomer.name;
                    } else {
                        this.formState.formData.customer = null;
                        this.formState.formData.customerName = null;
                    }
                    this.setCartHistory();
                })
                .catch(error => console.error(error))
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },


        /*
        * Customer create information.
        */
        showCustomerAddForm() {
            this.customerFormState.disabled = true;
            this.customerFormState.formData = {
                name: '',
                phone_number: '',
                email: '',
                address: '',
                status: true
            }
            this.customerFormState.validation = false;
            this.customerFormState.openCreate = true;
            this.customerFormState.disabled = false;
        },
        setData(customer = 'Walk-In') {
            if (this.formState.formData.customerName){
                customer = this.formState.formData.customerName
            }
            this.getCustomers(customer)
        },
        onClose() {
            this.customerFormState.openCreate = false;
        },
        setCartHistory() {
            const cartHistory = this.formState.formData;
            localStorage.setItem(this.user_email, JSON.stringify(cartHistory));
        },
        getCartHistory() {
            const cartHistory = localStorage.getItem(this.user_email);
            if (cartHistory) {
                this.formState.formData = JSON.parse(cartHistory)
            }
        }
    }
}
</script>

<style scoped>
.product-area:hover {
    box-shadow: 0.3em 0.3em 1em rgba(0, 0, 0, 0.3);
}

/* Add scrollbar to a tbody element */
.products-area-height-700 {
    min-height: 700px;
    max-height: 700px;
}

.products-area-height-400 {
    min-height: 400px;
    max-height: 400px;
}

.products-area {
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
    cursor: pointer;
    border-bottom: 1px #ededed solid;
}

/* Customize scrollbar appearance for WebKit browsers */
.products-area::-webkit-scrollbar {
    width: 8px; /* Set the width of the scrollbar */
}

.products-area::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.products-area::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.products-area:active::-webkit-scrollbar-thumb,
.products-area:focus::-webkit-scrollbar-thumb,
.products-area:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

/* Add scrollbar to a tbody element */

.cart-area-height-420 {
    min-height: 420px;
    max-height: 420px;
}

.cart-area-height-300 {
    min-height: 300px;
    max-height: 300px;
}

.cart-area {
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
    cursor: pointer;
    border-bottom: 1px #ededed solid;
}


/* Customize scrollbar appearance for WebKit browsers */
.cart-area::-webkit-scrollbar {
    width: 8px; /* Set the width of the scrollbar */
}

.cart-area::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.cart-area::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.cart-area:active::-webkit-scrollbar-thumb,
.cart-area:focus::-webkit-scrollbar-thumb,
.cart-area:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

figure {
    position: relative;
}

figure .status.online {
    background: #10b759;
}

figure .status.offline {
    background: #565555;
}

figure .status {
    width: 15px;
    height: 15px;
    background: #7987a1;
    position: absolute;
    bottom: 0px;
    right: 5px;
    border-radius: 50%;
    border: 2px solid #fff;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}


.stock-card:hover {
    box-shadow: 0.3em 0.3em 1em rgba(0, 0, 0, 0.3);
}

.readonly {
    pointer-events: none;
}

.list-group-item {
    padding: 0.2rem 0.2rem;
    border: 0;
}
</style>
