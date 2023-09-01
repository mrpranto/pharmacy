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
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <a-form-item :label="__('default.supplier')" required>
                                    <a-input-group compact
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

                            <div class="col-sm-12 col-md-4 col-lg-4">
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
                                            <a-radio-button value="Received">Received</a-radio-button>
                                            <a-radio-button value="Pending">Pending</a-radio-button>
                                            <a-radio-button value="Canceled">Canceled</a-radio-button>
                                        </a-radio-group>
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
                                            v-model:value="searchProduct" style="width: calc(100%)"
                                            show-search
                                            :placeholder="__('default.search_product')"
                                            :options="formState.dependencies.products"
                                            :filter-option="false"
                                            @search="getProducts"
                                            @change="selectProduct"
                                        >
                                            <template #option="{ value: val, label, icon, company, category, unit }">
                                                <a-image
                                                    :width="35"
                                                    :height="35"
                                                    :src="icon"
                                                    class="img-sm rounded-circle"
                                                    :aria-label="val"
                                                />
                                                &nbsp;&nbsp;
                                                <span><b>{{ label }}</b>,
                                                    <b>{{ __('default.company') }}:</b> <span class="text-muted">{{ company }}</span>,
                                                    <b>{{ __('default.category') }}:</b> <span class="text-muted">{{ category }}</span>,
                                                    <b>{{ __('default.unit') }}:</b> <span class="text-muted">{{ unit }}</span>
                                                </span>
                                            </template>
                                        </a-select>
                                    </a-input-group>
                                    <div class="ant-form-item-explain-error" style=""
                                         v-if="formState.validation.products">
                                        {{ formState.validation.products[0] }}
                                    </div>
                                </a-form-item>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--        <div class="row mb-2">
                    <div class="col-sm-12 col-md-7 col-lg-7">
                        <div class="card radius-20">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="20%">Product</th>
                                            <th width="15%" class="text-center">Unit Price ({{ $currency_symbol }})</th>
                                            <th width="15%" class="text-center">Sale Price ({{ $currency_symbol }})</th>
                                            <th width="10%" class="text-center">Quantity</th>
                                            <th width="15%" class="text-center">Discount ({{ $currency_symbol }})</th>
                                            <th width="15%" class="text-center">Sub total ({{ $currency_symbol }})</th>
                                            <th width="5%"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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

                                        <tr>
                                            <td width="5%">1</td>
                                            <td width="20%">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="font-weight-bolder text-capital">Product Name</p>
                                                        <p class="text-muted tx-13" :title="__('default.barcode')"><b>Bar:</b>112233445566
                                                        </p>
                                                        <p class="text-muted tx-13" :title="__('default.sku')"><b>SK:</b>
                                                            PR-120-21</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="10%">
                                                <a-input-number v-model:value="product_price" style="width: 110%"/>
                                            </td>
                                            <td width="15%" class="text-center">
                                                <p>{{ $showCurrency(122.00) }}, 20%</p>
                                                <a-tooltip :title="__('default.remove')">
                                                    <FormOutlined class="color-primary"/>
                                                </a-tooltip>
                                            </td>
                                            <td width="15%" class="text-center p-0">
                                                {{ $showCurrency(122000.00) }}
                                            </td>
                                            <td width="5%">
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
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-5 col-lg-5">
                        <div class="card radius-20">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>


                </div>-->


    </div>
</template>
<script>

import {DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined} from "@ant-design/icons-vue";
import dayjs from 'dayjs';
export default {
    name: "AddNewPurchase",
    components: {DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined},
    props: ['permission'],
    data() {
        return {
            loader: false,
            product_price: 100,
            searchProduct: null,
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
                            label: `${item.name} (Barcode: ${item.barcode})`,
                            value: item.id,
                            icon: `${item.product_photo ? item.product_photo?.full_url : '/images/medicine.png'}`,
                            company: item.company.name,
                            category: item.category.name,
                            unit: item.unit.name+ `(${item.unit.pack_size})`
                        }
                    })
                    this.formState.dependencies.products = searchProduct
                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectProduct() {
            this.formState.formData.products.push(this.searchProduct)
            this.searchProduct = null
            console.log(this.formState.formData.products)
        },
        showAddNewSupplierModal() {

        }
    }
}
</script>
<style scoped>
.table th, .table td {
    padding: 0.2rem 0.6rem;
}

</style>
