<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">
                                    {{ __('default.payment') }} {{ __('default.report') }}
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
                                              :class="request.payment_type.length ? 'active-button' : ''"
                                              class="dropdown-toggle" id="purchase_status"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.payment_type') }}
                                    </a-button>

                                    <form class="dropdown-menu p-4" aria-labelledby="purchase_status">
                                        <a-checkbox-group v-model:value="request.payment_type" style="width: 250px">
                                            <a-row>
                                                <a-col :span="12" v-for="(payment_type_label) in payment_types">
                                                    <a-checkbox :value="payment_type_label">
                                                        {{ payment_type_label }}
                                                    </a-checkbox>
                                                </a-col>
                                            </a-row>
                                        </a-checkbox-group>
                                        <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                           v-if="request.payment_type.length"
                                           @click.prevent="request.payment_type = []">
                                            <small>{{ __('default.clear') }}</small>
                                        </a>
                                    </form>
                                </div>

                                <div class="dropdown">
                                    <a-button type="dashed" shape="round" size="small"
                                              :class="request.cash_flow ? 'active-button' : ''"
                                              class="dropdown-toggle" id="payment_status"
                                              data-toggle="dropdown" aria-haspopup="true"
                                              aria-expanded="false" style="margin-right: 5px;">
                                        <template #icon>
                                            <i class="mdi mdi-plus-circle mr-1"></i>
                                        </template>
                                        {{ __('default.cash_flow') }}
                                    </a-button>
                                    <form class="dropdown-menu p-4" aria-labelledby="payment_status">
                                        <a-radio-group v-model:value="request.cash_flow" style="width: 250px">
                                            <a-row>
                                                <a-col :span="12" v-for="(cash_flow_label) in cash_flow">
                                                    <a-radio :value="cash_flow_label">
                                                        {{ cash_flow_label }}
                                                    </a-radio>
                                                </a-col>
                                            </a-row>
                                        </a-radio-group>
                                        <a class="text-primary mt-2 float-right text-decoration-none cursor-pointer"
                                           v-if="request.cash_flow"
                                           @click.prevent="request.cash_flow = null">
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
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-checkbox-multiple-marked-circle text-primary"></i>
                                        </h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.cash_flow') }} {{ __('default.in') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card radius-20 w-100 h-100 d-inline-block">
                            <div class="card-body">
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="list-card-icon">
                                        <h1><i class="mdi mdi-checkbox-multiple-blank-circle-outline text-primary"></i></h1>
                                    </div>
                                    <div class="pl-4">
                                        <p>{{ __('default.cash_flow') }} {{ __('default.out') }}</p>
                                        <h4 class="mt-2 font-weight-light">{{ $showCurrency(0) }}</h4>
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
                                      v-if="request.payment_type.length">
                                <template v-for="(payment_type, payment_type_index) in request.payment_type">
                                    {{
                                        payment_type_index === 0 ? payment_type.toUpperCase() : ', ' + payment_type.toUpperCase()
                                    }}
                                </template>
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.payment_type = []">
                                    <i class="mdi mdi-close-circle"></i>
                                </a>
                            </span>

                                <span class="pt-1 pl-3 pb-1 text-primary border bg-gray radius-20 mr-2"
                                      v-if="request.cash_flow">
                                {{ cash_flow.toUpperCase() }}
                                <a class="text-decoration-none cursor-pointer ml-2 mr-2"
                                   @click.prevent="request.cash_flow = []">
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
                                    <th class="bg-gray">{{ __('default.date') }}</th>
                                    <th class="bg-gray">{{ __('default.paid_amount') }}</th>
                                    <th class="bg-gray">{{ __('default.payment_type') }}</th>
                                    <th class="bg-gray">{{ __('default.payment_for') }}</th>
                                    <th class="bg-gray">{{ __('default.cash_flow') }}</th>
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
    name: "Payment",
    components: {CloseCircleFilled, SearchOutlined},
    props: [],
    data() {
        return {
            loader: false,
            rangePreset: [
                {label: 'Last 7 Days', value: [dayjs().add(-7, 'd'), dayjs()]},
                {label: 'Last 14 Days', value: [dayjs().add(-14, 'd'), dayjs()]},
                {label: 'Last 30 Days', value: [dayjs().add(-30, 'd'), dayjs()]},
                {label: 'Last 90 Days', value: [dayjs().add(-90, 'd'), dayjs()]},
            ],
            payment_types: [
                'CASH', 'BANK', 'BKASH', 'NAGOD'
            ],
            cash_flow: [
                'IN', 'OUT'
            ],
            request: {
                date: this.$today + ' to ' + this.$today,
                payment_type: [],
                cash_flow: null,
                search: null,
            },
            payment_reports: [],
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
        'request.payment_type': function () {
            this.getData()
        },
        'request.cash_flow': function () {
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
            await axios.get('/report/get-payment', {params: this.request})
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
            this.request.payment_type = [];
            this.request.cash_flow = [];
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
