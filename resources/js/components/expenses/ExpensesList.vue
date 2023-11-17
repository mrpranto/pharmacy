<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.expenses') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap" v-if="permission.create">
                                <button class="btn btn-primary btn-icon-text mb-2 mb-md-0" type="button" disabled
                                        v-if="formState.disabled">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                          aria-hidden="true"></span>
                                    {{ __('default.loading') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0"
                                        @click.prevent="showAddForm"
                                        v-else>
                                    <i class="mdi mdi-plus"></i>
                                    {{ __('default.add_expenses') }}
                                </button>
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

        <AddNewExpenses :formState="formState"/>
        <EditExpenses :formState="formState"/>

    </div>
</template>

<script>

import AddNewExpenses from "./AddNewExpenses.vue";
import dayjs from "dayjs";
import EditExpenses from "./EditExpenses.vue";

export default {
    name: 'ExpensesList',
    components: {EditExpenses, AddNewExpenses},
    props: ['permission'],
    data() {
        return {
            loader: false,
            formState: {
                openCreate: false,
                openEdit: false,
                disabled: false,
                current_id: '',
                list_path: '',
                current_list_url: '',
                selectAll: false,
                formData: {
                    title: '',
                    date: '',
                    details: '',
                    item_details: [],
                    total_amount: 0
                },
                layout: {
                    labelCol: {span: 4},
                    wrapperCol: {span: 20},
                },
                validation: {}
            },
            options: {
                loader: false,
                responseData: {},
                total: 0,
                columns: [
                    {
                        title: 'sl',
                        type: 'sl',
                        key: 'sl',
                        isVisible: false,
                        width: '5',
                    },
                    {
                        title: 'title',
                        type: 'text',
                        key: 'title',
                        orderAble: true,
                        isVisible: true,
                        width: '18',
                    },
                    {
                        title: 'date',
                        type: 'custom-html',
                        key: 'date',
                        width: '17',
                        orderAble: true,
                        isVisible: true,
                        modifier: (date) => {
                            return `<span>${this.$date_format(date)}</span><br><small>${this.$time_format(date)}</small>`;
                        }
                    },
                    {
                        title: 'details',
                        type: 'text',
                        key: 'details',
                        orderAble: true,
                        isVisible: true,
                        width: '20',
                    },
                    {
                        title: 'amount',
                        type: 'custom-html',
                        key: 'total_amount',
                        width: '10',
                        orderAble: true,
                        isVisible: true,
                        modifier: (total_amount) => {
                            return `<span>${this.$showCurrency(total_amount)}</span>`;
                        }
                    },
                    {
                        title: 'item_details',
                        type: 'component',
                        componentName: 'expenses-items-component',
                        key: 'item_details',
                        orderAble: false,
                        isVisible: true,
                        width: '10',
                    },
                    {
                        title: 'attachment',
                        type: 'custom-html',
                        key: 'expanse_attachment',
                        width: '10',
                        orderAble: false,
                        isVisible: true,
                        modifier: (expanse_attachment) => {
                            if (expanse_attachment){
                                return `<a href="${expanse_attachment.full_url}" download="true"
                                            class="badge badge-pill badge-dark"><i class="mdi mdi-download"></i> Download</a>`;
                            }
                        }
                    },
                    {
                        title: 'action',
                        type: 'action',
                        key: 'action',
                        permission: this.permission,
                        componentName: 'expenses-action-component',
                        isVisible: true,
                        width: '10',
                    },
                ],
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc',
                    date: ''
                },
                filters: [
                    {
                        title: 'date',
                        type: "date",
                        key: "date",
                        filterValue: null,
                    },
                ],
                exportAble: {}
            },
            show:{
                supplier:{},
                open:false
            }
        }
    },
    created() {
        this.getData()
    },
    watch:{
        'options.request.date': function () {
            this.getData()
        },
    },
    mounted() {

    },
    methods: {
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/get-expenses', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.formState.list_path = response.data.path
                    this.formState.current_list_url = response.data.current_page
                    this.options.loader = false;
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async showDetails(id) {
            this.loader = true
            await axios.get('/peoples/suppliers/' + id)
                .then(response => {
                    this.show.supplier = response.data
                    this.show.open = true
                    this.loader = false
                })
                .catch(err => {
                    console.error(err)
                })
        },
        showAddForm() {
            this.loader = true;
            this.formState.formData = {
                title: '',
                date: dayjs(this.$today_time, 'YYYY-MM-DD HH:mm:ss'),
                details: '',
                item_details: [],
                total_amount: 0
            }
            this.formState.validation = false;
            this.formState.openCreate = true;
            setTimeout(() => this.loader = false, 500)
        },
        filterData(filterValue, filterType) {
            if (filterType === 'date') {
                this.options.request.date = filterValue
            }
        },

        onClose() {
            this.formState.openCreate = false;
        },
        getEditData(expenses) {
            this.loader = true;
            this.formState.current_id = expenses.id;
            this.formState.formData = {
                title: expenses.title,
                date: dayjs(expenses.date, 'YYYY-MM-DD HH:mm:ss'),
                details: expenses.details,
                item_details: JSON.parse(expenses.item_details),
                total_amount: expenses.total_amount,
                attachment: null
            }
            this.formState.attachment = expenses.expanse_attachment?.full_url;
            this.formState.validation = {};
            this.formState.openEdit = true;
            setTimeout(() => this.loader = false, 500)
        },
        onEditClose() {
            this.formState.openEdit = false;
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
            await axios.delete(`/expanses/${id}`)
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

<style>
.bg-gray {
    background-color: #f2f4f9;
}

.btn-gray {
    border: 1px #ededed solid;
    height: 34px;
}

.btn-gray:hover {
    background-color: #838282;
    color: #FFFFFF;
}
</style>
