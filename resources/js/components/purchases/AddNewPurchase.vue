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


        </div>


    </div>
</template>
<script>

import {DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined} from "@ant-design/icons-vue";

export default {
    name: "AddNewPurchase",
    components: {DeleteOutlined, CloseCircleOutlined, QuestionCircleFilled, FormOutlined},
    props: ['permission'],
    data() {
        return {
            loader: false,
            product_price: 100
        }
    },
    created() {

    },
    mounted() {
        console.log(this.$showCurrency(1200))
    },
    methods: {
        confirm() {
            alert('hello')
        }
    }
}
</script>
<style scoped>
.table th, .table td {
    padding: 0.2rem 0.6rem;
}

</style>
