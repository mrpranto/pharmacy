<template>
    <div>
        <a-modal v-model:open="show.open"
                 width="80%"
                 style="top: 10px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">

            <div class="row mt-5" id="printarea">
                <div class="col-sm-12">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-6 pl-0">
                            <h5 class="mb-2 text-muted">{{ __('default.supplier') }} : {{
                                    show.purchase.supplier.name
                                }}</h5>
                            <p>{{ __('default.phone_number') }} : {{ show.purchase.supplier.phone_number }},
                                {{ __('default.email') }} : {{ show.purchase.supplier.email }}</p>
                            <p>{{ show.purchase.supplier.address }}</p>
                        </div>
                        <div class="col-lg-6 pr-0">
                            <h6 class="text-right pb-4"># INV-{{ show.purchase.reference }}</h6>
                            <h4 class="text-right font-weight-normal">{{ __('default.total') }}:
                                {{ $showCurrency(show.purchase.total) }}</h4>
                            <h6 class="mb-0 mt-3 text-right font-weight-normal mb-2">
                                <p><span class="text-muted">{{ __('default.date') }} :</span>
                                    {{ $date_format(show.purchase.date) }}</p>
                                <p>
                                    <span class="text-muted">{{ __('default.status') }} : </span>
                                    <span v-if="show.purchase.status === 'received'"
                                          class="badge badge-primary">{{ show.purchase.status.toUpperCase() }}</span>
                                    <span v-else-if="show.purchase.status === 'pending'"
                                          class="badge badge-warning">{{ show.purchase.status.toUpperCase() }}</span>
                                    <span v-else class="badge badge-danger">{{
                                            show.purchase.status.toUpperCase()
                                        }}</span>
                                </p>
                            </h6>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table table-hover table-striped border">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('default.product') }}</th>
                                    <th class="text-right">{{ __('default.unit_price') }}</th>
                                    <th class="text-right">{{ __('default.sale_price') }}</th>
                                    <th class="text-right">{{ __('default.quantity') }}</th>
                                    <th class="text-right">{{ __('default.discount') }}</th>
                                    <th class="text-right">{{ __('default.sub_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-right"
                                    v-for="(purchase_product, purchase_product_index) in show.purchase.purchase_products"
                                    :key="purchase_product_index">
                                    <td class="text-left">{{ (purchase_product_index + 1) }}</td>
                                    <td width="20%" class="text-left">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="corner-up-left" id="backToChatList"
                                               class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                            <figure class="mb-0 mr-2">
                                                <a-image :width="35" :height="35"
                                                         :src="purchase_product.product.product_photo?.full_url ?? '/images/medicine.png'"
                                                         class="img-sm rounded-circle"
                                                         :alt="purchase_product.product.name"/>
                                                <div class="status online"></div>
                                            </figure>
                                            <div>
                                                <p class="font-weight-bolder text-capital">
                                                    {{ purchase_product.product.name }}
                                                    <small class="text-muted" :title="__('default.unit')">
                                                        {{ purchase_product.product.unit.name }}
                                                        ({{ purchase_product.product.unit.pack_size }})
                                                    </small>
                                                </p>
                                                <p class="text-muted tx-13"><b>{{
                                                        __('default.barcode')
                                                    }}: </b>{{ purchase_product.product.barcode }}</p>
                                                <p class="text-muted tx-13">
                                                                <span :title="__('default.company')">{{
                                                                        purchase_product.product.company.name
                                                                    }}</span>,
                                                    <span :title="__('default.category')">{{
                                                            purchase_product.product.category.name
                                                        }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $showCurrency(purchase_product.unit_price) }}</td>
                                    <td>{{ $showCurrency(purchase_product.sale_price) }}</td>
                                    <td>{{ purchase_product.quantity }}</td>
                                    <td>
                                        {{ purchase_product.discount }} {{ purchase_product.discount_type }}
                                    </td>
                                    <td>
                                        {{ $showCurrency(purchase_product.subTotal) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 w-100">
                        <div class="row">
                            <div class="col-md-6 ml-auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>{{ __('default.sub_total') }}</td>
                                            <td class="text-right">{{ $showCurrency(show.purchase.subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('default.other_cost') }}</td>
                                            <td class="text-right">{{ $showCurrency(show.purchase.otherCost) }}</td>
                                        </tr>
                                        <tr>
                                            <td>(-) {{ __('default.discount') }}</td>
                                            <td class="text-right">{{ $showCurrency(show.purchase.discount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800 h3">{{ __('default.total') }}</td>
                                            <td class="text-bold-800 text-right h3">
                                                {{ $showCurrency(show.purchase.total) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="container-fluid w-100">
                        <button class="btn btn-danger float-right" @click.prevent="show.open = false">
                            <i class="mdi mdi-backspace"></i> {{ __('default.close') }}
                        </button>

                        <button class="btn btn-primary float-right mr-2" @click.prevent="print">
                            <i class="mdi mdi-printer"></i> {{ __('default.print') }}
                        </button>
                    </div>
                </div>
            </div>

        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';

export default {
    name: "PurchaseDetails",
    components: {DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props: ['show'],
    methods: {
        print() {
            window.open('/purchase-print/'+this.show.purchase.id, '_blank');
        }
    }
}
</script>
<style scoped>

</style>
