
<template>
    <div>
        <a-modal v-model:open="show.open"
                 width="80%"
                 style="top: 10px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">

            <div class="row mt-3">
                <div class="col-sm-12">
                    <div class="container-fluid d-flex justify-content-between">
                        <div class="col-lg-3 pl-0">
                            <h5 class="mb-2 text-muted">{{ __('default.supplier') }} : {{ show.purchase.supplier.name }}</h5>
                            <p>{{ __('default.phone_number') }} : {{ show.purchase.supplier.phone_number }}, {{ __('default.email') }} : {{ show.purchase.supplier.email }}</p>
                            <p>{{ show.purchase.supplier.address }}</p>
                        </div>
                        <div class="col-lg-3 pr-0">
                            <h6 class="text-right pb-4"># INV-{{ show.purchase.reference }}</h6>
                            <h4 class="text-right font-weight-normal">{{ __('default.total') }}: {{ $showCurrency(show.purchase.total) }}</h4>
                            <h6 class="mb-0 mt-3 text-right font-weight-normal mb-2"><span class="text-muted">{{ __('default.date') }} :</span> {{ $date_format(show.purchase.date) }}</h6>
                        </div>
                    </div>
                    <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                        <div class="table-responsive w-100">
                            <table class="table table-hover table-bordered">
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
                                <tr class="text-right">
                                    <td class="text-left">1</td>
                                    <td class="text-left">PSD to html conversion</td>
                                    <td>02</td>
                                    <td>$55</td>
                                    <td>$110</td>
                                    <td>$110</td>
                                    <td>$110</td>
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
                                            <td>Sub Total</td>
                                            <td class="text-right">$ 14,900.00</td>
                                        </tr>
                                        <tr>
                                            <td>TAX (12%)</td>
                                            <td class="text-right">$ 1,788.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold-800">Total</td>
                                            <td class="text-bold-800 text-right"> $ 16,688.00</td>
                                        </tr>
                                        <tr>
                                            <td>Payment Made</td>
                                            <td class="text-danger text-right">(-) $ 4,688.00</td>
                                        </tr>
                                        <tr class="bg-light">
                                            <td class="text-bold-800">Balance Due</td>
                                            <td class="text-bold-800 text-right">$ 12,000.00</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid w-100">
                        <a href="#" class="btn btn-primary float-right mt-4 ml-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send mr-3 icon-md"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>Send Invoice</a>
                        <a href="#" class="btn btn-outline-primary float-right mt-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer mr-2 icon-md"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>Print</a>
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
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['show'],
}
</script>
<style scoped>

</style>
