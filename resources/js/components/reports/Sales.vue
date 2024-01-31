<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">
                                    {{ __('default.sales') }} {{ __('default.report') }}
                                    <span class="ml-1 font-weight-lighter">| {{ __('default.total') }} : {{
                                            total_sales
                                        }}</span>
                                </h5>
                            </div>
                            <div class="ml-3 d-flex justify-content-start">
                                <a-button type="dashed" shape="round" size="small" class="daterange"
                                          :class="request.date ? 'active-button' : ''" style="margin-right: 5px;">
                                    <template #icon>
                                        <i class="mdi mdi-plus-circle mr-1"></i>
                                    </template>
                                    {{ __('default.date') }}
                                </a-button>

                                <div class="dropdown">
                                    <a-button type="dashed" shape="round" size="small"
                                              :class="request.customer ? 'active-button' : ''"
                                              class="dropdown-toggle" id="supplier"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.customer') }}
                                    </a-button>
                                    <form class="dropdown-menu p-4" aria-labelledby="supplier">
                                        <a-form-item :label="__('default.customer')" required style="margin-bottom: 0">
                                            <a-select
                                                v-model:value="request.customer"
                                                show-search
                                                placeholder="Select a person"
                                                style="width: 250px"
                                                :options="customers"
                                                :filter-option="selectFilterOption"
                                            ></a-select>
                                            <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                               v-if="request.customer"
                                               @click.prevent="request.customer = null">
                                                <small>{{ __('default.clear') }}</small>
                                            </a>
                                        </a-form-item>
                                    </form>
                                </div>

                                <div class="dropdown">
                                    <a-button type="dashed" shape="round" size="small"
                                              :class="request.sale_status.length ? 'active-button' : ''"
                                              class="dropdown-toggle" id="purchase_status"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.sale_status') }}
                                    </a-button>

                                    <form class="dropdown-menu p-4" aria-labelledby="purchase_status">
                                        <a-checkbox-group v-model:value="request.sale_status" style="width: 250px">
                                            <a-row>
                                                <a-col :span="12" v-for="(sale_status_label) in sale_status">
                                                    <a-checkbox :value="sale_status_label">
                                                        {{ __('default.' + sale_status_label) }}
                                                    </a-checkbox>
                                                </a-col>
                                            </a-row>
                                        </a-checkbox-group>
                                        <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                           v-if="request.sale_status.length"
                                           @click.prevent="request.sale_status = []">
                                            <small>{{ __('default.clear') }}</small>
                                        </a>
                                    </form>
                                </div>

                                <div class="dropdown">
                                    <a-button type="dashed" shape="round" size="small"
                                              :class="request.payment_status.length ? 'active-button' : ''"
                                              class="dropdown-toggle" id="payment_status"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.payment_status') }}
                                    </a-button>
                                    <form class="dropdown-menu p-4" aria-labelledby="payment_status">
                                        <a-checkbox-group v-model:value="request.payment_status" style="width: 270px">
                                            <a-row>
                                                <a-col :span="12" v-for="(payment_status_label) in payment_status">
                                                    <a-checkbox :value="payment_status_label">
                                                        {{ __('default.' + payment_status_label) }}
                                                    </a-checkbox>
                                                </a-col>
                                            </a-row>
                                        </a-checkbox-group>
                                        <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                           v-if="request.payment_status.length"
                                           @click.prevent="request.payment_status = []">
                                            <small>{{ __('default.clear') }}</small>
                                        </a>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-2">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-apps text-primary"></i>
                                        </h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.unit_qty') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ (totalQuantity) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-checkbox-multiple-blank-circle-outline text-primary"></i>
                                        </h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.subtotal') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(totalSubtotal) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-checkbox-multiple-marked-circle text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.grand_total') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(totalGrandTotal) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-credit-card-multiple text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.payment') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(totalPaid) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-credit-card-off text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total_due_amount') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(totalDue) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4 mt-2">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-currency-usd text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total_profit_amount') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(totalProfit) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-2">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="mb-3">
                                <a-button type="default" :size="'small'"
                                          shape="round" class="mr-2"
                                          :title="__('default.refresh')"
                                          @click.prevent="refresh">
                                    <template #icon>
                                        <i class="mdi mdi-reload"></i>
                                    </template>
                                </a-button>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.date">
                                {{ request.date }}
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.date = null">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.customer">
                                {{ customers.find(item => item.value === request.customer).label }}
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.customer = null">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.sale_status.length">
                                <template v-for="(sale_status, sale_status_index) in request.sale_status">
                                    {{
                                        sale_status_index === 0 ? sale_status.toUpperCase() : ', ' + sale_status.toUpperCase()
                                    }}
                                </template>
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.sale_status = []">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.payment_status.length">
                                <template v-for="(payment_status, payment_status_index) in request.payment_status">
                                    {{
                                        payment_status_index === 0 ? payment_status.toUpperCase() : ', ' + payment_status.toUpperCase()
                                    }}
                                </template>
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.payment_status = []">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>
                            </div>
                            <div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a-input v-model:value="request.search"
                                                 style="border-radius: 20px;"
                                                 size="small"
                                                 @pressEnter="searchData"
                                                 :placeholder="__('default.search')+'...'">
                                            <template #suffix>
                                                <search-outlined style="color: rgba(0, 0, 0, 0.45)"
                                                                 v-if="!request.search"/>
                                                <a-tooltip title="Clear Search" placement="left"
                                                           @click.prevent="clearSearch" v-else>
                                                    <close-circle-filled style="color: rgba(0, 0, 0, 0.45)"/>
                                                </a-tooltip>
                                            </template>
                                        </a-input>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-gray">{{ __('default.sl') }}</th>
                                    <th class="bg-gray">{{ __('default.invoice_number') }}</th>
                                    <th class="bg-gray">{{ __('default.customer') }}</th>
                                    <th class="bg-gray">{{ __('default.date') }}</th>
                                    <th class="bg-gray">{{ __('default.unit_qty') }}</th>
                                    <th class="bg-gray">{{ __('default.subtotal') }}</th>
                                    <th class="bg-gray">{{ __('default.total_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.paid_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.due_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.sale_status') }}</th>
                                    <th class="bg-gray">{{ __('default.payment_status') }}</th>
                                    <th class="bg-gray">{{ __('default.total_profit_amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-if="loader">
                                    <td colspan="12" class="text-center">
                                        <a-spin :tip="__('default.loading')"/>
                                    </td>
                                </tr>
                                <template v-else>
                                    <template v-if="sales.length">
                                        <tr v-for="(sale, sale_index) in sales">
                                            <td>{{ (sale_index + 1) }}</td>
                                            <td>
                                                <a :href="'/invoice-pdf/'+sale.id" target="_blank"
                                                   class="text-decoration-none font-bold">
                                                    {{ sale.invoice_number }} <span class="ml-1"><i
                                                    class="mdi mdi-call-made"></i></span>
                                                </a>
                                            </td>
                                            <td>{{ sale.customer.name }} <br>
                                                <template v-if="sale.customer.phone_number">
                                                    ({{ sale.customer.phone_number }})
                                                </template>
                                            </td>
                                            <td>
                                                {{ $date_format(sale.invoice_date) }} <br>
                                                <small>{{ $time_format(sale.invoice_date) }}</small>
                                            </td>
                                            <td>
                                                {{ sale.total_unit_qty }}
                                            </td>
                                            <td>{{ $showCurrency(sale.subtotal) }}</td>
                                            <td>{{ $showCurrency(sale.grand_total) }}</td>
                                            <td>{{ $showCurrency(sale.total_paid) }}</td>
                                            <td>{{ $showCurrency(sale.totalDue) }}</td>
                                            <td>
                                            <span class="badge badge-info" v-if="sale.status === 'CONFIRMED'">
                                                {{ sale.status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-warning" v-else-if="sale.status === 'DRAFT'">
                                                {{ sale.status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-danger" v-else-if="sale.status === 'CANCELED'">
                                                {{ sale.status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-success" v-else>
                                                {{ sale.status.toUpperCase() }}
                                            </span>
                                            </td>
                                            <td>
                                            <span class="badge badge-danger" v-if="sale.payment_status === 'DUE'">
                                                {{ sale.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-info"
                                                      v-else-if="sale.payment_status === 'PARTIAL-PAID'">
                                                {{ sale.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-warning"
                                                      v-else-if="sale.payment_status === 'OVER-DUE'">
                                                {{ sale.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-success" v-else>
                                                {{ sale.payment_status.toUpperCase() }}
                                            </span>
                                            </td>
                                            <td>
                                                {{ $showCurrency(sale.totalRevenue) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                {{ __('default.total') }}
                                                {{ totalCalculate }}
                                            </th>
                                            <th>{{ (calculated.total_unit_qty) }}</th>
                                            <th>{{ $showCurrency(calculated.total_subtotal) }}</th>
                                            <th>{{ $showCurrency(calculated.total_grand_total) }}</th>
                                            <th>{{ $showCurrency(calculated.total_paid) }}</th>
                                            <th>{{ $showCurrency(calculated.total_due) }}</th>
                                            <th colspan="2"></th>
                                            <th>{{ $showCurrency(calculated.total_profit) }}</th>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="12" class="text-center">
                                            {{ __('default.no_data_found') }}
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dayjs from 'dayjs';
import {CloseCircleFilled, SearchOutlined} from "@ant-design/icons-vue";
import {time_format} from "../../helper.js";

export default {
    name: "Sales",
    components: {CloseCircleFilled, SearchOutlined},
    props: ['customers'],
    data() {
        return {
            loader: false,
            rangePreset: [
                {label: 'Last 7 Days', value: [dayjs().add(-7, 'd'), dayjs()]},
                {label: 'Last 14 Days', value: [dayjs().add(-14, 'd'), dayjs()]},
                {label: 'Last 30 Days', value: [dayjs().add(-30, 'd'), dayjs()]},
                {label: 'Last 90 Days', value: [dayjs().add(-90, 'd'), dayjs()]},
            ],
            sale_status: [
                'CONFIRMED', 'DRAFT', 'CANCELED', 'DELIVERED'
            ],
            payment_status: [
                'PAID', 'DUE', 'PARTIAL-PAID', 'OVER-DUE'
            ],
            request: {
                date: this.$today + ' to ' + this.$today,
                customer: null,
                sale_status: [],
                payment_status: [],
                search: null,
            },
            sales: [],
            totalQuantity: 0,
            totalSubtotal: 0,
            totalGrandTotal: 0,
            totalPaid: 0,
            totalDue: 0,
            total_sales: 0,
            totalProfit: 0,
            calculated: {
                total_unit_qty: 0,
                total_subtotal: 0,
                total_grand_total: 0,
                total_paid: 0,
                total_due: 0,
                total_profit: 0,
            }
        }
    },
    created() {
        this.getData()
    },
    watch: {
        'request.date': function () {
            this.getData()
        },
        'request.customer': function () {
            this.getData()
        },
        'request.sale_status': function () {
            this.getData()
        },
        'request.payment_status': function () {
            this.getData()
        },
    },
    mounted() {
        const self = this;
        this.$nextTick(() => {
            $(".filter-column").click(function (e) {
                e.stopPropagation();
            })
            $('.daterange').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            }, function (start, end, label) {
                self.request.date = start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD');
            });
        });
    },
    computed: {
        totalCalculate() {
            this.calculated.total_unit_qty = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.total_unit_qty);
            }, 0);

            this.calculated.total_subtotal = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.subtotal);
            }, 0);

            this.calculated.total_grand_total = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.grand_total);
            }, 0);

            this.calculated.total_paid = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.total_paid);
            }, 0);

            this.calculated.total_due = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.totalDue);
            }, 0);

            this.calculated.total_profit = this.sales.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.totalRevenue);
            }, 0);
        }
    },
    methods: {
        time_format,
        async getData() {
            this.loader = true;
            await axios.get('/report/get-sale', {params: this.request})
                .then(response => {
                    this.sales = response.data.sales;
                    this.totalQuantity = response.data.totalQuantity;
                    this.totalSubtotal = response.data.totalSubtotal;
                    this.totalGrandTotal = response.data.totalGrandTotal;
                    this.totalPaid = response.data.totalPaid;
                    this.totalDue = response.data.totalDue;
                    this.totalProfit = response.data.totalProfit;
                    this.total_sales = response.data.total_sales;
                    this.loader = false;
                })
                .catch(err => console.error(err))
        },
        async refresh() {
            this.request.date = this.$today + ' to ' + this.$today;
            this.request.supplier = null;
            this.request.purchase_status = [];
            this.request.payment_status = [];
            this.request.search = null;
            await this.getData();
        },
        async searchData() {
            await this.getData()
        },
        async clearSearch() {
            this.request.search = '';
            await this.getData();
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
    }
}
</script>
<style scoped>
.active-button {
    color: #1677ff;
    border-color: #1677ff;
}

.dropdown-toggle::after {
    content: none;
}

.dropdown-menu {
    margin-top: 8px;
    border-radius: 5px !important;
    border: 1px solid #ddd;
}

.dropdown-menu:before, .dropdown-menu:after {
    position: absolute;
    display: inline-block;
    border-bottom-color: rgba(0, 0, 0, 0.2);
    content: '';
}

.dropdown-menu:before {
    top: -7px;
    border-right: 7px solid transparent;
    border-left: 7px solid transparent;
    border-bottom: 7px solid #ccc;
}

.dropdown-menu:after {
    top: -6px;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #fff;
    border-left: 6px solid transparent;
}

.dropdown-menu:before {
    left: 17px;
}

.dropdown-menu:after {
    left: 18px;
}
</style>
