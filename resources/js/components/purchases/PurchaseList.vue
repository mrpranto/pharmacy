<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.purchase_list') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <a href="/purchases/create" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_purchase') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi mdi-cart text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.received') }} {{ __('default.purchase') }}</p>
                                <h4 class="mt-2 font-weight-light">{{ $showCurrency(options.received.total_amount ?? 0) }}</h4>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.total_purchase') }} : {{ options.received.total_purchase }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.unit') }} : {{ options.received.total_unit }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{__('default.quantity') }} : {{ options.received.total_quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-basket-fill text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.pending') }} {{ __('default.purchase') }}</p>
                                <h4 class="mt-2 font-weight-light">{{ $showCurrency(options.pending.total_amount ?? 0) }}</h4>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.total_purchase') }} : {{ options.pending.total_purchase }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.unit') }} : {{ options.pending.total_unit }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.quantity') }} : {{ options.pending.total_quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-close-circle text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.canceled') }} {{ __('default.purchase') }}</p>
                                <h4 class="mt-2 font-weight-light">{{ $showCurrency(options.canceled.total_amount ?? 0) }}</h4>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.total_purchase') }} : {{ options.canceled.total_purchase }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.unit') }} : {{ options.canceled.total_unit }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.quantity') }} : {{ options.canceled.total_quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                <div class="card radius-20 w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="list-card-icon">
                                <h1><i class="mdi mdi-chart-bar text-primary"></i></h1>
                            </div>
                            <div class="pl-4">
                                <p>{{ __('default.total') }} {{ __('default.purchase') }}</p>
                                <h4 class="mt-2 font-weight-light">{{ $showCurrency(options.all.total_amount ?? 0) }}</h4>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.total_purchase') }} : {{ options.all.total_purchase }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.unit') }} : {{ options.all.total_unit }}</span>
                                <span class="badge badge-primary badge-pill m-1">{{ __('default.quantity') }} : {{ options.all.total_quantity }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row inbox-wrapper">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <app-table-component :options="options"></app-table-component>
                    </div>
                </div>
            </div>
        </div>

        <PurchaseDetails :show="show"/>

        <a-modal v-model:open="payment.open"
                 width="900px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.add_payment')">
            <div class="row pt-4 border-top">
                <div class="col-12">
                    <dl class="row">
                        <dt class="col-sm-4 text-right">{{ __('default.purchase_reference') }}</dt>
                        <dd class="col-sm-8">: {{ payment.purchase_info.reference }}</dd>

                        <dt class="col-sm-4 text-right">{{ __('default.invoice_date') }}</dt>
                        <dd class="col-sm-8">: {{ $date_format(payment.purchase_info.date) }} </dd>

                        <dt class="col-sm-4 text-right">{{ __('default.supplier') }}</dt>
                        <dd class="col-sm-8">: {{ payment.purchase_info.supplier.name }} <template v-if="payment.purchase_info.supplier.phone_number">({{ payment.purchase_info.supplier.phone_number }})</template></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.subtotal') }}</dt>
                        <dd class="col-sm-8">: <b>{{ $showCurrency(payment.purchase_info.subtotal) }}</b></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.total') }}</dt>
                        <dd class="col-sm-8">: <b>{{ $showCurrency(payment.purchase_info.total) }}</b></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.status') }}</dt>
                        <dd class="col-sm-8">:
                            <span v-if="payment.purchase_info.status === 'received'" class="badge badge-primary">{{ payment.purchase_info.status.toUpperCase() }}</span>
                            <span v-else-if="payment.purchase_info.status === 'pending'" class="badge badge-warning">{{ payment.purchase_info.status.toUpperCase() }}</span>
                            <span v-else-if="payment.purchase_info.status === 'canceled'" class="badge badge-danger">{{ payment.purchase_info.status.toUpperCase() }}</span>
                        </dd>

                        <dt class="col-sm-4 text-right">{{ __('default.payment_status') }}</dt>
                        <dd class="col-sm-8">:
                            <span class="badge badge-danger" v-if="payment.purchase_info.payment_status === 'DUE'">{{ payment.purchase_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-info" v-else-if="payment.purchase_info.payment_status === 'PARTIAL-PAID'">{{ payment.purchase_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-warning" v-else-if="payment.purchase_info.payment_status === 'OVER-DUE'">{{ payment.purchase_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-success" v-else>{{ payment.purchase_info.payment_status.toUpperCase() }}</span>
                        </dd>
                    </dl>
                </div>

                <div class="col-12">
                    <table class="table table-striped border">
                        <thead>
                        <tr>
                            <th width="15%">{{ __('default.payment_type') }}</th>
                            <th>{{ __('default.paid_amount') }}</th>
                            <th>{{ __('default.bank_name') }}</th>
                            <th>{{ __('default.account_number') }}</th>
                            <th>{{ __('default.transaction_number') }}</th>
                            <th class="text-center">
                                <PlusCircleOutlined
                                    @click.prevent="addNewPayment"
                                    class="cursor-pointer color-primary"
                                    :style="{fontSize: '20px'}"/>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(payment_data, payment_data_index) in payment.formData">
                            <td>
                                <a-select v-model:value="payment_data.type"
                                          @change="setPaymentType(payment_data_index, payment_data.type)"
                                          :placeholder="__('default.payment_type')"
                                          style="width: 100%;">
                                    <a-select-option v-for="(type, type_index) in payment_type" :key="type_index" :value="type">{{ type }}</a-select-option>
                                </a-select>
                            </td>
                            <td>
                                <a-input v-model:value="payment_data.paid_amount" class="text-right" type="number"/>
                            </td>
                            <td>
                                <a-input v-if="payment_data.hideAccountArea === false" v-model:value="payment_data.bank_name"/>
                            </td>
                            <td>
                                <a-input v-if="payment_data.hideAccountArea === false" v-model:value="payment_data.account_number"/>
                            </td>
                            <td>
                                <a-input v-if="payment_data.hideAccountArea === false" v-model:value="payment_data.transaction_number"/>
                            </td>
                            <td>
                                <MinusCircleOutlined
                                    @click.prevent="removePayment(payment_data_index)"
                                    class="cursor-pointer color-danger"
                                    :style="{fontSize: '20px'}"/>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot v-if="payment.formData.length">
                        <tr>
                            <th>{{ __('default.total') }} : </th>
                            <th class="text-right">{{ $showCurrency(totalPaidAmount) }}</th>
                            <th colspan="4"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="col-12 pt-3">
                    <button class="btn btn-primary float-right" @click.prevent="savePayment">
                        <i class="mdi mdi-check-circle"></i> {{ __('default.save') }}
                    </button>
                </div>
            </div>
        </a-modal>

    </div>
</template>
<script>

import PurchaseDetails from "./PurchaseDetails.vue";
import {MinusCircleOutlined, PlusCircleOutlined} from "@ant-design/icons-vue";
import {message} from "ant-design-vue";

export default {
    name: "PurchaseList",
    components: {PlusCircleOutlined, MinusCircleOutlined, PurchaseDetails},
    props: ['permission', 'payment_type'],
    data() {
        return {
            loader: false,
            options: {
                loader: false,
                responseData: {},
                total: 0,
                received: {},
                pending: {},
                canceled: {},
                all: {},
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        key: 'sl',
                        width: '5',
                        isVisible: false
                    },
                    {
                        title: 'purchase_reference',
                        type: 'custom-html',
                        key: 'reference',
                        width: '13',
                        orderAble: true,
                        isVisible: true,
                        modifier: (purchase_reference) => {
                            return purchase_reference
                        }
                    },
                    {
                        title: 'supplier',
                        type: 'custom-html',
                        key: 'supplier',
                        width: '12',
                        orderAble: true,
                        isVisible: true,
                        modifier: (supplier) => {
                            return `<span>${supplier.name}<br/><small>(${supplier.phone_number})</small></span>`
                        }
                    },
                    {
                        title: 'date',
                        type: 'custom-html',
                        key: 'date',
                        width: '13',
                        orderAble: true,
                        isVisible: true,
                        modifier: (date) => {
                            return this.$date_format(date);
                        }
                    },
                    {
                        title: 'subtotal',
                        type: 'custom-html',
                        key: 'subtotal',
                        width: '10',
                        orderAble: true,
                        isVisible: true,
                        modifier: (subtotal) => {
                            return this.$showCurrency(subtotal);
                        }
                    },
                    {
                        title: 'unit_qty',
                        type: 'custom-html',
                        key: 'purchase_products',
                        width: '10',
                        orderAble: false,
                        isVisible: true,
                        modifier: (purchase_products) => {
                            let totalQty = 0;
                            purchase_products.forEach(item => {
                                totalQty += parseFloat(item.quantity)
                            })
                            return `<span>Unit : <b>${purchase_products.length}</b></span><br><span>Qty : <b>${totalQty}</b></span>`;
                        }
                    },
                    {
                        title: 'total',
                        type: 'custom-html',
                        key: 'total',
                        width: '10',
                        orderAble: true,
                        isVisible: true,
                        modifier: (total) => {
                            return this.$showCurrency(total);
                        }
                    },
                    {
                        title: 'status',
                        type: 'custom-html',
                        key: 'status',
                        width: '10',
                        orderAble: true,
                        isVisible: true,
                        modifier: (status) => {
                            if (status === 'received') {
                                return `<span class="badge badge-primary">${status.toUpperCase()}</span>`;
                            } else if (status === 'pending') {
                                return `<span class="badge badge-warning">${status.toUpperCase()}</span>`;
                            } else {
                                return `<span class="badge badge-danger">${status.toUpperCase()}</span>`;
                            }
                        }
                    },
                    {
                        title: 'payment_status',
                        type: 'custom-html',
                        key: 'payment_status',
                        width: '11',
                        orderAble: true,
                        isVisible: true,
                        modifier: (payment_status, row) => {
                            if (payment_status === 'DUE') {
                                return `<span class="badge badge-danger" title="${this.$showCurrency(row.total_paid)}">${payment_status}</span>`;
                            } else if (payment_status === 'PARTIAL-PAID') {
                                return `<span class="badge badge-info" title="${this.$showCurrency(row.total_paid)}">${payment_status}</span>`;
                            }  else if (payment_status === 'OVER-DUE') {
                                return `<span class="badge badge-warning" title="${this.$showCurrency(row.total_paid)}">${payment_status}</span>`;
                            } else {
                                return `<span class="badge badge-success" title="${this.$showCurrency(row.total_paid)}">${payment_status}</span>`;
                            }
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'purchase-action-component',
                        width: '6',
                        isVisible: true
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc',
                    date: '',
                    supplier: '',
                    purchase_status: '',
                    payment_status: '',
                },
                exportAble: {
                    csv: '',
                    excel: '',
                    pdf: '',
                    print: ''
                },
                filters: [
                    {
                        title: 'date',
                        type: "date",
                        key: "date",
                        filterValue: null,
                    },
                    {
                        title: 'supplier',
                        type: "drop-down-filter",
                        key: "supplier",
                        filterValue: 'supplier',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'purchase_status',
                        type: "button-checkbox",
                        key: "purchase_status",
                        filterValue: '',
                        option: [
                            'received', 'pending', 'canceled'
                        ],
                    },
                    {
                        title: 'payment_status',
                        type: "button-checkbox",
                        key: "payment_status",
                        filterValue: '',
                        option: [
                            'PAID', 'DUE', 'PARTIAL-PAID', 'OVER-DUE'
                        ],
                    },
                ],
            },
            show: {
                purchase: {},
                permission: this.permission,
                open: false
            },
            payment: {
                open: false,
                loader: false,
                current_id: null,
                purchase_info: {},
                totalPaid: 0,
                paymentStatus: null,
                formData:[],
                validation:{}
            }
        }
    },
    created() {
        this.getData()
        this.getSuppliers()
    },
    mounted() {
    },
    watch: {
        'options.request.supplier': function () {
            this.getData()
        },
        'options.request.purchase_status': function () {
            this.getData()
        },
        'options.request.payment_status': function () {
            this.getData()
        },
        'options.request.date': function () {
            this.getData()
        }
    },
    computed: {
        totalPaidAmount() {
            let totalPaidAmount = 0;
            this.payment.formData.forEach(item => {
                const paidAmount = item.paid_amount === null ? 0 : parseFloat(item.paid_amount);
                totalPaidAmount += paidAmount;
            });

            this.payment.totalPaid = parseFloat(totalPaidAmount.toFixed(2));

            if (this.payment.purchase_info.total === this.payment.totalPaid){
                this.payment.paymentStatus = 'PAID';
            }else if (this.payment.totalPaid !== 0.00 && this.payment.purchase_info.total > this.payment.totalPaid) {
                this.payment.paymentStatus = 'PARTIAL-PAID';
            }else if (this.payment.purchase_info.total < this.payment.totalPaid) {
                this.payment.paymentStatus = 'OVER-DUE';
            }else {
                this.payment.paymentStatus = 'DUE';
            }
            return this.payment.totalPaid;
        }
    },
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/get-purchases', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data.purchase;
                    this.options.total = response.data.purchase.total;
                    this.options.received = response.data.received;
                    this.options.pending = response.data.pending;
                    this.options.canceled = response.data.canceled;
                    this.options.all = response.data.all;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getSuppliers() {
            await axios.get('/get-purchases-suppliers')
                .then(response => {
                    this.options.filters.find(item => item.key === 'supplier').option = response.data.map(item => {
                        return {
                            label: item.name,
                            value: item.id
                        }
                    });
                })
                .catch(err => {
                    console.error(err)
                })
        },
        filterData(filterValue, filterType) {
            if (filterType === 'supplier') {
                this.options.request.supplier = filterValue
            }
            if (filterType === 'purchase_status') {
                this.options.request.purchase_status = filterValue
            }
            if (filterType === 'payment_status') {
                this.options.request.payment_status = filterValue
            }
            if (filterType === 'date') {
                this.options.request.date = filterValue
            }
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/purchases/' + id)
                .then(response => {
                    this.show.purchase = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showDeleteForm(id) {
            const swalWithBootstrapButtons = Swal.mixin()
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'question',
                width: 400,
                showCancelButton: true,
                confirmButtonClass: 'ml-2',
                confirmButtonText: ' <i class="mdi mdi-check"></i> Yes, delete it!',
                cancelButtonText: '<i class="mdi mdi-close"></i> No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    this.delete(id)
                }
            })
        },
        async delete(id) {
            await axios.delete(`/purchases/${id}`)
                .then(response => {
                    if (response.data.success) {
                        this.getData()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    this.$showErrorMessage(err.data.error, this.$notification_position, this.$notification_sound)
                })
        },
        showAddPaymentForm(id, row){
            this.payment.open = true;
            this.payment.current_id = id;
            this.payment.purchase_info = row;
            this.payment.formData = row.payments.map(item => {
                const newItem = {
                    id: item.id,
                    paid_amount: item.paid_amount,
                    type: item.type,
                    bank_name: item.bank_name,
                    account_number: item.account_number,
                    transaction_number: item.transaction_number,
                    hideAccountArea: item.type === "CASH" ? true : false
                }
                return newItem
            })
        },
        addNewPayment(){
            this.payment.formData.push({
                paid_amount: null,
                type: null,
                bank_name: null,
                account_number: null,
                transaction_number: null,
                hideAccountArea: false
            })
        },
        removePayment(key){
            message.warning('Payment information remove successful.');
            this.payment.formData.splice(key, 1);
        },
        setPaymentType(key, value) {
            const payment = this.payment.formData[key];
            const existHasThisPaymentType = this.payment.formData.filter(item => item.type === value);
            const paymentType = payment.type;
            if (existHasThisPaymentType.length > 1){
                message.error('Already added this payment type.');
                this.payment.formData[key].type = null
            }else {
                if (paymentType === 'CASH') {
                    payment.hideAccountArea = true
                }else {
                    payment.hideAccountArea = false
                }
            }
        },
        async savePayment(){
            this.payment.loader = true
            await axios.post('/purchase-payment', this.payment)
                .then(response => {
                    if (response.data.success) {
                        this.payment.validation = {};
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound);
                        this.payment.open = false;
                        this.payment.loader = true;
                        this.getData()
                    }else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound);
                        this.payment.validation = {};
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound);
                        this.payment.validation = err.response.data.errors;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err);
                    }
                })
        }
    }
}
</script>
<style scoped>

</style>
