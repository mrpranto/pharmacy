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

        <div class="row inbox-wrapper">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <app-table-component :options="options"></app-table-component>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>

import moment from "moment";
import dayjs from 'dayjs';
export default {
    name: "PurchaseList",
    components: {},
    props: ['permission'],
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
                        title: 'purchase_reference',
                        type: 'custom-html',
                        key: 'reference',
                        width: '13',
                        orderAble: true,
                        isVisible: true ,
                        modifier: (purchase_reference) => {
                            return purchase_reference
                        }
                    },
                    {
                        title: 'supplier',
                        type: 'custom-html',
                        key: 'supplier',
                        width: '15',
                        orderAble: true,
                        isVisible: true ,
                        modifier: (supplier) => {
                            return  `<span>${supplier.name}<br/><small>(${supplier.phone_number})</small></span>`
                        }
                    },
                    {
                        title: 'date',
                        type: 'custom-html',
                        key: 'date',
                        width: '15',
                        orderAble: true,
                        isVisible: true ,
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
                        isVisible: true ,
                        modifier: (subtotal) => {
                            return this.$showCurrency(subtotal);
                        }
                    },
                    {
                        title: 'discount',
                        type: 'custom-html',
                        key: 'discount',
                        width: '10',
                        orderAble: true,
                        isVisible: true ,
                        modifier: (discount) => {
                            return this.$showCurrency(discount);
                        }
                    },
                    {
                        title: 'total',
                        type: 'custom-html',
                        key: 'total',
                        width: '10',
                        orderAble: true,
                        isVisible: true ,
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
                        isVisible: true ,
                        modifier: (status) => {
                            if (status === 'received'){
                                return `<span class="badge badge-primary">${status.toUpperCase()}</span>`;
                            }else if(status === 'pending'){
                                return `<span class="badge badge-warning">${status.toUpperCase()}</span>`;
                            }else {
                                return `<span class="badge badge-danger">${status.toUpperCase()}</span>`;
                            }
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'product-action-component',
                        width: '12',
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
                ],
            },
            show:{
                purchase:{},
                open:false
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
        'options.request.supplier': function (){
            this.getData()
        },
        'options.request.purchase_status': function (){
            this.getData()
        },
        'options.request.date': function (){
            this.getData()
        }
    },
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/get-purchases', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
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
            if (filterType === 'date') {
                this.options.request.date = filterValue
            }
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/product/products/' + id)
                .then(response => {
                    this.show.product = response.data
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
            await axios.delete(`/product/products/${id}`)
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
        }
    }
}
</script>
<style scoped>

</style>
