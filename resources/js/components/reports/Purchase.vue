<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">
                                    {{ __('default.purchase') }} {{ __('default.report') }}
                                    <span class="ml-1 font-weight-lighter">| {{ __('default.total') }} : {{ total_purchase }}</span>
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
                                              :class="request.supplier ? 'active-button' : ''"
                                              class="dropdown-toggle" id="supplier"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.supplier') }}
                                    </a-button>
                                    <form class="dropdown-menu p-4" aria-labelledby="supplier">
                                        <a-form-item :label="__('default.supplier')" required style="margin-bottom: 0">
                                            <a-select
                                                v-model:value="request.supplier"
                                                show-search
                                                placeholder="Select a person"
                                                style="width: 250px"
                                                :options="suppliers"
                                                :filter-option="selectFilterOption"
                                            ></a-select>
                                            <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                               v-if="request.supplier"
                                               @click.prevent="request.supplier = null">
                                                <small>{{ __('default.clear') }}</small>
                                            </a>
                                        </a-form-item>
                                    </form>
                                </div>

                                <div class="dropdown">
                                    <a-button type="dashed" shape="round" size="small"
                                              :class="request.purchase_status.length ? 'active-button' : ''"
                                              class="dropdown-toggle" id="purchase_status"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.purchase_status') }}
                                    </a-button>

                                    <form class="dropdown-menu p-4" aria-labelledby="purchase_status">
                                        <a-checkbox-group v-model:value="request.purchase_status" style="width: 250px">
                                            <a-row>
                                                <a-col :span="12" v-for="(purchase_status_label) in purchase_status">
                                                    <a-checkbox :value="purchase_status_label">
                                                        {{ __('default.' + purchase_status_label) }}
                                                    </a-checkbox>
                                                </a-col>
                                            </a-row>
                                        </a-checkbox-group>
                                        <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                           v-if="request.purchase_status.length"
                                           @click.prevent="request.purchase_status = []">
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
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-checkbox-multiple-blank-circle-outline text-primary"></i>
                                        </h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.subtotal') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(total_subtotal) }}</h4>
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
                                        <h1><i class="mdi mdi-checkbox-multiple-marked-circle text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.grand_total') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(total_grand_total) }}</h4>
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
                                        <h1><i class="mdi mdi-credit-card-multiple text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total') }} {{ __('default.payment') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(total_paid) }}</h4>
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
                                        <h1><i class="mdi mdi-credit-card-off text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.total_due_amount') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(total_due) }}</h4>
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

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2" v-if="request.date">
                                {{ request.date }}
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.date = null">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.supplier">
                                {{ suppliers.find(item => item.value === request.supplier).label }}
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.supplier = null">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.purchase_status.length">
                                <template v-for="(purchase_status, purchase_status_index) in request.purchase_status">
                                    {{
                                        purchase_status_index === 0 ? purchase_status.toUpperCase() : ', ' + purchase_status.toUpperCase()
                                    }}
                                </template>
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.purchase_status = []">
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
                                                <search-outlined style="color: rgba(0, 0, 0, 0.45)" v-if="!request.search"/>
                                                <a-tooltip title="Clear Search" placement="left" @click.prevent="clearSearch" v-else>
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
                                    <th class="bg-gray">{{ __('default.purchase_reference') }}</th>
                                    <th class="bg-gray">{{ __('default.supplier') }}</th>
                                    <th class="bg-gray">{{ __('default.date') }}</th>
                                    <th class="bg-gray">{{ __('default.subtotal') }}</th>
                                    <th class="bg-gray">{{ __('default.total_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.paid_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.due_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.purchase_status') }}</th>
                                    <th class="bg-gray">{{ __('default.payment_status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-if="loader">
                                    <td colspan="10" class="text-center">
                                        <a-spin :tip="__('default.loading')"/>
                                    </td>
                                </tr>
                                <template v-else>
                                    <template v-if="purchase_reports.length">
                                        <tr v-for="(purchase, purchase_index) in purchase_reports">
                                            <td>{{ (purchase_index + 1) }}</td>
                                            <td>
                                                <a :href="'/purchase-print/'+purchase.id" target="_blank"
                                                   class="text-decoration-none font-bold">
                                                    {{ purchase.reference }} <span class="ml-1"><i class="mdi mdi-call-made"></i></span>
                                                </a>
                                            </td>
                                            <td>{{ purchase.supplier_name }} <br> ({{ purchase.supplier_phone_number }})
                                            </td>
                                            <td>{{ $date_format(purchase.date) }}</td>
                                            <td>{{ $showCurrency(purchase.subtotal) }}</td>
                                            <td>{{ $showCurrency(purchase.total) }}</td>
                                            <td>{{ $showCurrency(purchase.total_paid) }}</td>
                                            <td>{{ $showCurrency(purchase.total_due) }}</td>
                                            <td>
                                            <span class="badge badge-primary" v-if="purchase.status === 'received'">
                                                {{ purchase.status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-warning" v-else-if="purchase.status === 'pending'">
                                                {{ purchase.status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-danger" v-else>
                                                {{ purchase.status.toUpperCase() }}
                                            </span>
                                            </td>
                                            <td>
                                            <span class="badge badge-danger" v-if="purchase.payment_status === 'DUE'">
                                                {{ purchase.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-info"
                                                      v-else-if="purchase.payment_status === 'PARTIAL-PAID'">
                                                {{ purchase.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-warning"
                                                      v-else-if="purchase.payment_status === 'OVER-DUE'">
                                                {{ purchase.payment_status.toUpperCase() }}
                                            </span>
                                                <span class="badge badge-success" v-else>
                                                {{ purchase.payment_status.toUpperCase() }}
                                            </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                {{ __('default.total') }}
                                                {{ totalCalculate }}
                                            </th>
                                            <th>{{ $showCurrency(calculated.total_subtotal) }}</th>
                                            <th>{{ $showCurrency(calculated.total_grand_total) }}</th>
                                            <th>{{ $showCurrency(calculated.total_paid) }}</th>
                                            <th>{{ $showCurrency(calculated.total_due) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="10" class="text-center">
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

export default {
    name: "Purchase",
    components: {CloseCircleFilled, SearchOutlined},
    props: ['suppliers'],
    data() {
        return {
            loader: false,
            rangePreset: [
                {label: 'Last 7 Days', value: [dayjs().add(-7, 'd'), dayjs()]},
                {label: 'Last 14 Days', value: [dayjs().add(-14, 'd'), dayjs()]},
                {label: 'Last 30 Days', value: [dayjs().add(-30, 'd'), dayjs()]},
                {label: 'Last 90 Days', value: [dayjs().add(-90, 'd'), dayjs()]},
            ],
            purchase_status: [
                'received', 'pending', 'canceled'
            ],
            payment_status: [
                'PAID', 'DUE', 'PARTIAL-PAID', 'OVER-DUE'
            ],
            request: {
                date: this.$today + ' to ' + this.$today,
                supplier: null,
                purchase_status: [],
                payment_status: [],
                search: null,
            },
            purchase_reports: [],
            total_subtotal: 0,
            total_grand_total: 0,
            total_paid: 0,
            total_due: 0,
            total_purchase: 0,
            calculated:{
                total_subtotal: 0,
                total_grand_total: 0,
                total_paid: 0,
                total_due: 0,
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
        'request.supplier': function () {
            this.getData()
        },
        'request.purchase_status': function () {
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
        totalCalculate(){
            this.calculated.total_subtotal = this.purchase_reports.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.subtotal);
            }, 0);

            this.calculated.total_grand_total = this.purchase_reports.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.total);
            }, 0);

            this.calculated.total_paid = this.purchase_reports.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.total_paid);
            }, 0);

            this.calculated.total_due = this.purchase_reports.reduce((accumulator, item) => {
                return parseFloat(accumulator) + parseFloat(item.total_due);
            }, 0);
        }
    },
    methods: {
        async getData() {
            this.loader = true;
            await axios.get('/report/get-purchase', {params: this.request})
                .then(response => {
                    this.purchase_reports = response.data.purchase_reports;
                    this.total_subtotal = response.data.total_subtotal;
                    this.total_grand_total = response.data.total_grand_total;
                    this.total_paid = response.data.total_paid;
                    this.total_due = response.data.total_due;
                    this.total_purchase = response.data.total_purchase;
                    this.loader = false;
                })
                .catch(err => console.error(err))
        },
        async refresh(){
            this.request.date = this.$today + ' to ' + this.$today;
            this.request.supplier = null;
            this.request.purchase_status = [];
            this.request.payment_status = [];
            this.request.search = null;
            await this.getData();
        },
        async searchData(){
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
