<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.sales_list') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <a href="/sales/create" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_sale') }}
                                </a>
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

        <a-modal v-model:open="changeStatus.open"
                 width="1000px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.change_status')">
            <div class="row pt-2">
                <div class="col-sm-3">
                    <a-popconfirm placement="top" title="Are you sure, you want to draft this ?" ok-text="Yes"
                                  cancel-text="No"
                                  @confirm="applyStatus('DRAFT')">
                        <template #icon></template>
                        <button class="btn btn-warning btn-block"
                                :class="changeStatus.current_status === 'DRAFT' ? 'disabled cursor-not-allowed' : ''"
                                type="button"
                                style="height: 100px;">
                            <i class="mdi mdi-pause"></i> Draft
                        </button>
                    </a-popconfirm>
                </div>
                <div class="col-sm-3">
                    <a-popconfirm placement="top" title="Are you sure, you want to confirm this ?" ok-text="Yes"
                                  cancel-text="No"
                                  @confirm="applyStatus('CONFIRMED')">
                        <template #icon></template>
                        <button class="btn btn-info btn-block"
                                :class="changeStatus.current_status === 'CONFIRMED' ? 'disabled cursor-not-allowed' : ''"
                                type="button"
                                style="height: 100px;">
                            <i class="mdi mdi-check-circle"></i> Confirmed
                        </button>
                    </a-popconfirm>
                </div>
                <div class="col-sm-3">
                    <a-popconfirm placement="top" title="Are you sure, you want to deliver this ?" ok-text="Yes"
                                  cancel-text="No"
                                  @confirm="applyStatus('DELIVERED')">
                        <template #icon></template>
                        <button class="btn btn-success btn-block"
                                :class="changeStatus.current_status === 'DELIVERED' ? 'disabled cursor-not-allowed' : ''"
                                type="button"
                                style="height: 100px;">
                            <i class="mdi mdi-truck-delivery"></i> Delivered
                        </button>
                    </a-popconfirm>
                </div>
                <div class="col-sm-3">
                    <a-popconfirm placement="top" title="Are you sure, you want to cancel this ?" ok-text="Yes"
                                  cancel-text="No"
                                  @confirm="applyStatus('CANCELED')">
                        <template #icon></template>
                        <button class="btn btn-danger btn-block"
                                :class="changeStatus.current_status === 'CANCELED' ? 'disabled cursor-not-allowed' : ''"
                                type="button"
                                style="height: 100px;">
                            <i class="mdi mdi-cancel"></i> Canceled
                        </button>
                    </a-popconfirm>
                </div>
            </div>
        </a-modal>


        <a-modal v-model:open="show.open"
                 width="1000px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.invoice_details')">
            <div class="row pt-2" id="printArea">

            </div>
        </a-modal>


        <a-modal v-model:open="payment.open"
                 width="900px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.add_payment')">
            <div class="row pt-4 border-top">
                <div class="col-12">
                    <dl class="row">
                        <dt class="col-sm-4 text-right">{{ __('default.invoice_number') }}</dt>
                        <dd class="col-sm-8">: {{ payment.sale_info.invoice_number }}</dd>

                        <dt class="col-sm-4 text-right">{{ __('default.invoice_date') }}</dt>
                        <dd class="col-sm-8">: {{ $date_format(payment.sale_info.invoice_date) }} <small> {{ $time_format(payment.sale_info.invoice_date) }}</small></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.customer') }}</dt>
                        <dd class="col-sm-8">: {{ payment.sale_info.customer.name }} <template v-if="payment.sale_info.customer.phone_number">({{ payment.sale_info.customer.phone_number }})</template></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.subtotal') }}</dt>
                        <dd class="col-sm-8">: <b>{{ $showCurrency(payment.sale_info.subtotal) }}</b></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.total') }}</dt>
                        <dd class="col-sm-8">: <b>{{ $showCurrency(payment.sale_info.grand_total) }}</b></dd>

                        <dt class="col-sm-4 text-right">{{ __('default.status') }}</dt>
                        <dd class="col-sm-8">:
                            <span v-if="payment.sale_info.status === 'CONFIRMED'" class="badge badge-info">{{ payment.sale_info.status.toUpperCase() }}</span>
                            <span v-else-if="payment.sale_info.status === 'DRAFT'" class="badge badge-warning">{{ payment.sale_info.status.toUpperCase() }}</span>
                            <span v-else-if="payment.sale_info.status === 'CANCELED'" class="badge badge-danger">{{ payment.sale_info.status.toUpperCase() }}</span>
                            <span v-else class="badge badge-success">{{ payment.sale_info.status.toUpperCase() }}</span>
                        </dd>

                        <dt class="col-sm-4 text-right">{{ __('default.payment_status') }}</dt>
                        <dd class="col-sm-8">:
                            <span class="badge badge-danger" v-if="payment.sale_info.payment_status === 'DUE'">{{ payment.sale_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-info" v-else-if="payment.sale_info.payment_status === 'PARTIAL-PAID'">{{ payment.sale_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-warning" v-else-if="payment.sale_info.payment_status === 'OVER-DUE'">{{ payment.sale_info.payment_status.toUpperCase() }}</span>
                            <span class="badge badge-success" v-else>{{ payment.sale_info.payment_status.toUpperCase() }}</span>
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


import {time_format} from "../../helper.js";
import {DollarOutlined, PlusCircleOutlined, MinusCircleOutlined} from "@ant-design/icons-vue";
import {message} from "ant-design-vue";

export default {
    name: "SaleList",
    components: {DollarOutlined, PlusCircleOutlined, MinusCircleOutlined},
    props: ['permission', 'payment_type'],
    data() {
        return {
            loader: false,
            options: {
                loader: false,
                responseData: {},
                total: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        key: 'sl',
                        width: '5',
                        isVisible: false
                    },
                    {
                        title: 'in_num',
                        type: 'custom-html',
                        key: 'invoice_number',
                        width: '12',
                        orderAble: true,
                        isVisible: true,
                        modifier: (purchase_reference) => {
                            return `<b>${purchase_reference}</b>`;
                        }
                    },
                    {
                        title: 'customer',
                        type: 'custom-html',
                        key: 'customer',
                        width: '12',
                        orderAble: true,
                        isVisible: true,
                        modifier: (customer) => {
                            if (customer.phone_number) {
                                return `<span>${customer.name}<br/><small>(${customer.phone_number})</small></span>`;
                            } else {
                                return `<span>${customer.name}</span>`;
                            }
                        }
                    },
                    {
                        title: 'invoice_date',
                        type: 'custom-html',
                        key: 'invoice_date',
                        width: '15',
                        orderAble: true,
                        isVisible: true,
                        modifier: (invoice_date) => {
                            return `<span>${this.$date_format(invoice_date)}</span><br><small>${this.$time_format(invoice_date)}</small>`;
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
                        key: 'total_unit_qty',
                        width: '10',
                        orderAble: false,
                        isVisible: true,
                        modifier: (total_unit_qty) => {
                            return `<span>Unit Qty : <b>${total_unit_qty}</b></span>`;
                        }
                    },
                    {
                        title: 'total',
                        type: 'custom-html',
                        key: 'grand_total',
                        width: '10',
                        orderAble: true,
                        isVisible: true,
                        modifier: (grand_total) => {
                            return this.$showCurrency(grand_total);
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
                            if (status === 'CONFIRMED') {
                                return `<span class="badge badge-info">${status.toUpperCase()}</span>`;
                            } else if (status === 'DRAFT') {
                                return `<span class="badge badge-warning">${status.toUpperCase()}</span>`;
                            } else if (status === 'CANCELED') {
                                return `<span class="badge badge-danger">${status.toUpperCase()}</span>`;
                            } else {
                                return `<span class="badge badge-success">${status.toUpperCase()}</span>`;
                            }
                        }
                    },
                    {
                        title: 'payment_status',
                        type: 'custom-html',
                        key: 'payment_status',
                        width: '10',
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
                        componentName: 'sale-action-component',
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
                    customer: '',
                    sale_status: '',
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
                        title: 'customer',
                        type: "drop-down-filter",
                        key: "customer",
                        filterValue: 'customer',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'sale_status',
                        type: "button-checkbox",
                        key: "sale_status",
                        filterValue: '',
                        option: [
                            'CONFIRMED', 'DRAFT', 'CANCELED', 'DELIVERED'
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
                open: false
            },
            changeStatus: {
                open: false,
                current_id: null,
                current_status: null
            },
            payment: {
                open: false,
                loader: false,
                current_id: null,
                sale_info: {},
                totalPaid: 0,
                paymentStatus: null,
                formData:[],
                validation:{}
            }
        }
    },
    created() {
        this.getData()
        this.getCustomers()
    },
    mounted() {
    },
    watch: {
        'options.request.customer': function () {
            this.getData()
        },
        'options.request.sale_status': function () {
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

            if (this.payment.sale_info.grand_total === this.payment.totalPaid){
                this.payment.paymentStatus = 'PAID';
            }else if (this.payment.totalPaid !== 0.00 && this.payment.sale_info.grand_total > this.payment.totalPaid) {
                this.payment.paymentStatus = 'PARTIAL-PAID';
            }else if (this.payment.sale_info.grand_total < this.payment.totalPaid) {
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
            await axios.get(url ?? '/get-sales-list', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getCustomers() {
            await axios.get('/get-sales-customers')
                .then(response => {
                    this.options.filters.find(item => item.key === 'customer').option = response.data.map(item => {
                        return {
                            label: item.name + `(${item.phone_number})`,
                            value: item.id
                        }
                    });
                })
                .catch(err => {
                    console.error(err)
                })
        },
        filterData(filterValue, filterType) {
            if (filterType === 'customer') {
                this.options.request.customer = filterValue
            }
            if (filterType === 'sale_status') {
                this.options.request.sale_status = filterValue
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
            this.show.open = true
            await axios.get('/sales/' + id)
                .then(response => {
                    setTimeout(() => {
                        document.getElementById('printArea').innerHTML = response.data;
                        this.loader = false
                    }, 200)
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
            await axios.delete(`/sales/${id}`)
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
        showStatusForm(id, row) {
            this.loader = true;
            this.changeStatus.open = true;
            this.changeStatus.current_id = row.id;
            this.changeStatus.current_status = row.status;
            if (this.changeStatus.open === true) {
                this.loader = false;
            }
        },
        async applyStatus(status) {
            this.loader = true;
            await axios.post('/change-status/' + this.changeStatus.current_id, {status: status})
                .then(response => {
                    if (response.data.success) {
                        this.changeStatus.open = false;
                        this.getData()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.loader = false;
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    this.$showErrorMessage(err.data.error, this.$notification_position, this.$notification_sound)
                })
        },
        showAddPaymentForm(id, row) {
            this.payment.open = true;
            this.payment.current_id = id;
            this.payment.sale_info = row;
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
            await axios.post('/sales-payment', this.payment)
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
