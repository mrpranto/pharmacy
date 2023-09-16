<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">{{ __('default.stock_list') }}
                                    <app-table-counter-component :total="options.total"/>
                                </h5>
                            </div>
                            <div>
                                <a-spin v-if="loader"/>
                            </div>
                            <div class="d-flex align-items-center flex-wrap text-nowrap"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row inbox-wrapper">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12 col-md-10 col-lg-10">
                                        <div class="btn-group">
                                            <button type="button" :title="__('default.refresh')"
                                                    class="btn btn-rounded btn-gray mr-1"
                                                    @click.prevent="refreshTable">
                                                <i class="mdi mdi-reload"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-2 col-lg-2">
                                        <a-input v-model:value="options.request.search"
                                                 style="border-radius: 20px;"
                                                 autofocus
                                                 @pressEnter="searchData"
                                                 :placeholder="__('default.search')+'...'">
                                            <template #suffix>
                                                <search-outlined style="color: rgba(0, 0, 0, 0.45)"
                                                                 v-if="!options.request.search"/>
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
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-fixed border">
                                        <thead>
                                        <tr>
                                            <td width="30%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.product') }}
                                                 </span>
                                            </td>
                                            <td width="20%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.company') }}
                                                 </span>
                                            </td>
                                            <td width="15%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.category') }}
                                                 </span>
                                            </td>
                                            <td width="15%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.unit') }}
                                                 </span>
                                            </td>
                                            <td width="12%" class="text-center">
                                                 <span class="font-bold">
                                                {{ __('default.total_available_qty') }}
                                                 </span>
                                            </td>
                                            <td width="8%"></td>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody-scroll">
                                            <template v-for="(product, product_index) in options.responseData.data">
                                                <tr class="data-tr" :class="isEven(product_index) ? 'row-color' : ''">
                                                    <td width="30%">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <i data-feather="corner-up-left" id="backToChatList"
                                                                   class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                                                                <figure class="mb-0 mr-2">
                                                                    <template v-if="product.product_photo">
                                                                        <a-image
                                                                            :width="35"
                                                                            :height="35"
                                                                            :src="product.product_photo?.full_url"
                                                                            class="img-sm rounded-circle"
                                                                            :alt="product.name"
                                                                        />
                                                                    </template>
                                                                    <template v-else>
                                                                        <a-image
                                                                            :width="35"
                                                                            :height="35"
                                                                            src="/images/medicine.png"
                                                                            class="img-sm rounded-circle"
                                                                            :alt="product.name"
                                                                        />
                                                                    </template>

                                                                    <div class="status online" v-if="product.status === 1"></div>
                                                                    <div class="status offline" v-else></div>
                                                                </figure>
                                                                <div>
                                                                    <p class="font-weight-bolder text-capital">{{ product.name }}</p>
                                                                    <p class="text-muted tx-13"><b>{{ __('default.barcode') }}:</b> {{ product?.barcode }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="20%">{{ product.company.name }}</td>
                                                    <td width="15%">{{ product.category.name }}</td>
                                                    <td width="15%">{{ product.unit.name }} ({{ product.unit.pack_size }})</td>
                                                    <td width="12%" class="text-center">
                                                        <span class="font-bold">
                                                        1220
                                                        </span>
                                                    </td>
                                                    <td width="8%" class="text-center">
                                                        <a data-toggle="collapse"
                                                           :data-target="'#stock'+product_index"
                                                           class="accordion-toggle">


                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 width="24"
                                                                 height="24"
                                                                 viewBox="0 0 24 24"
                                                                 fill="none"
                                                                 stroke="currentColor"
                                                                 stroke-width="2"
                                                                 stroke-linecap="round"
                                                                 stroke-linejoin="round"
                                                                 class="feather feather-corner-down-left">
                                                                <polyline points="9 10 4 15 9 20"></polyline>
                                                                <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                                                            </svg>

                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="hiddenRow">
                                                        <div class="accordian-body collapse overflow-hidden" :id="'stock'+product_index">
                                                            <div class="row">
                                                                <div class="col-sm-10 p-4 mx-auto">
                                                                    <div class="row mb-3">
                                                                        <div class="col-sm-12">
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <div class="d-flex align-items-center">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                         width="34" height="34"
                                                                                         viewBox="0 0 24 24"
                                                                                         fill="none"
                                                                                         stroke="currentColor"
                                                                                         stroke-width="1"
                                                                                         stroke-linecap="round"
                                                                                         stroke-linejoin="round"
                                                                                         class="feather feather-layers">
                                                                                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                                                                        <polyline points="2 17 12 22 22 17"></polyline>
                                                                                        <polyline points="2 12 12 17 22 12"></polyline>
                                                                                    </svg>


                                                                                    <div class="ml-3">
                                                                                        <p style="font-size: 18px">Product have 4 different types of stock </p>
                                                                                        <p class="tx-14 text-muted">
                                                                                            <span class="mr-3"><b>Total Purchase Quantity : </b> 100,</span>
                                                                                            <span class="mr-3"><b>Total Sale quantity : </b> 200,</span>
                                                                                            <span class="mr-3"><b>Total Available Quantity : </b> 400</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-striped border">
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>{{ __('default.unit_price') }}</th>
                                                                                        <th>{{ __('default.sale_price') }}</th>
                                                                                        <th>{{ __('default.purchase_quantity') }}</th>
                                                                                        <th>{{ __('default.sale_quantity') }}</th>
                                                                                        <th>{{ __('default.available_quantity') }}</th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>1</th>
                                                                                        <td>Mark</td>
                                                                                        <td>Otto</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>2</th>
                                                                                        <td>Mark</td>
                                                                                        <td>Otto</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>3</th>
                                                                                        <td>Mark</td>
                                                                                        <td>Otto</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>4</th>
                                                                                        <td>Mark</td>
                                                                                        <td>Otto</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>5</th>
                                                                                        <td>Mark</td>
                                                                                        <td>Otto</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                        <td>@mdo</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12 col-md-12 col-lg-6 mt-2 d-flex align-items-center">
                                <div class="btn-group" role="group">
                                    <div class="dropdown show mr-1">
                                        <a class="btn btn-gray btn-radius dropdown-toggle"
                                           href="javascript:void(0)"
                                           id="dropdownMenuLink"
                                           data-toggle="dropdown"
                                           aria-haspopup="true"
                                           aria-expanded="false">
                                            {{ options.request.per_page }} / page
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="javascript:void(0)"
                                               v-for="(per_page, per_page_index) in per_pages"
                                               @click.prevent="changePerPage(per_page)"
                                               :key="per_page_index">{{ per_page }} / page</a>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    {{ __('default.showing') }} {{ from }} {{ __('default.to') }} {{ to }} {{
                                        __('default.of')
                                    }}
                                    {{ total }} {{ __('default.entries') }}
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-6 mt-2">
                                <div class="btn-toolbar justify-content-lg-end">
                                    <div class="input-group mr-3">
                                        <a-select
                                            v-model:value="page"
                                            show-search
                                            placeholder="Pages"
                                            style="width: 100%"
                                            :options="pages"
                                            @change="getPaginate"
                                        ></a-select>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-gray btn btn-icon-text btn-left-radius"
                                           :title="__('default.first_page')"
                                           :class="options.responseData.current_page === 1 ? 'disabled' : ''"
                                           @click.prevent="getFirstPage">
                                            <i class="mdi mdi-chevron-double-left"></i>
                                        </a>
                                        <a class="btn btn-gray btn btn-icon-text"
                                           :title="__('default.previous_page')"
                                           :class="options.responseData.prev_page_url == null ? 'disabled' : ''"
                                           @click.prevent="getPreviousPage">
                                            <i class="mdi mdi-chevron-left"></i>
                                        </a>
                                        <a class="btn btn-gray btn btn-icon-text"
                                           :title="__('default.next_page')"
                                           :class="options.responseData.next_page_url == null ? 'disabled' : ''"
                                           @click.prevent="getNextPage">
                                            <i class="mdi mdi-chevron-right"></i>
                                        </a>
                                        <a class="btn btn-gray btn btn-icon-text btn-right-radius"
                                           :title="__('default.last_page')"
                                           :class="options.responseData.last_page === options.responseData.current_page ? 'disabled' : ''"
                                           @click.prevent="getLastPage">
                                            <i class="mdi mdi-chevron-double-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import {CloseCircleFilled, SearchOutlined, EnterOutlined} from "@ant-design/icons-vue";
export default {
    name: 'StockList',
    components: {SearchOutlined, CloseCircleFilled, EnterOutlined},
    data() {
        return {
            loader: false,
            per_pages: [
                5,
                10,
                20,
                30,
                50,
                100
            ],
            page: {
                value: 1,
                label: 1
            },
            pages: [],
            from: '',
            to: '',
            dataArray:[],

            options: {
                loader: false,
                responseData: {},
                total: 0,
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc'
                },
                exportAble: {}
            },
        }
    },
    created() {
        this.getData()
    },
    mounted() {

    },
    methods: {
        isEven(n) {
            if ((n % 2) === 0) {
                return true
            } else {
                return false
            }
        },
        async getData(url) {
            this.options.loader = true;
            this.options.responseData = [];
            await axios.get(url ?? '/get-stocks', {params: this.options.request})
                .then(response => {
                    this.options.responseData = response.data;
                    this.options.total = response.data.total;
                    this.options.loader = false;

                    console.log(this.options.responseData)
                })
                .catch(err => {
                    console.error(err)
                })
        },


        /*
        * Pagination function
        */
        changePerPage(page) {
            this.page = {
                value: 1,
                label: 1
            }
            this.options.request.per_page = page
        },
        async getPaginate(value) {
            const page = value
            const requestUrl = this.options.responseData?.path + '?page=' + page;
            await this.getData(requestUrl)
        },
        async getPreviousPage() {
            const requestUrl = this.options.responseData?.prev_page_url;
            const page = (parseInt(this.options.responseData?.current_page) - 1) === 0 ? 1 : (parseInt(this.options.responseData?.current_page) - 1)
            this.reAssignPage(page)
            await this.getData(requestUrl)
        },
        async getNextPage() {
            const requestUrl = this.options.responseData?.next_page_url;
            const page = (parseInt(this.options.responseData?.current_page) + 1)
            this.reAssignPage(page)
            await this.getData(requestUrl)
        },
        async getFirstPage() {
            const requestUrl = this.options.responseData?.first_page_url;
            this.reAssignPage(1)
            await this.getData(requestUrl)
        },
        async getLastPage() {
            const requestUrl = this.options.responseData?.last_page_url;
            this.reAssignPage(parseInt(this.options.responseData?.last_page))
            await this.getData(requestUrl)
        },
    }
}
</script>

<style scoped>
figure {
    position: relative;
}

figure .status.online {
    background: #10b759;
}
figure .status.offline {
    background: #565555;
}

figure .status {
    width: 11px;
    height: 11px;
    background: #7987a1;
    position: absolute;
    bottom: 0px;
    right: -2px;
    border-radius: 50%;
    border: 2px solid #fff;
}
.font-bold {
    font-weight: bolder;
    font-size: 15px;
}

.btn-gray {
    border: 1px #e3e2e2 solid;
    height: 34px;
    color: #706f6f;
}

.btn-gray:hover {
    background-color: #838282;
    color: #FFFFFF;
}

.btn-left-radius {
    border-radius: 5px 0 0 5px;
}

.btn-right-radius {
    border-radius: 0 5px 5px 0;
}

.btn-radius {
    border-radius: 5px;
}

/* Add scrollbar to an element */
.scroll {
    max-height: 200px; /* Set the maximum height of the element */
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
}

/* Customize scrollbar appearance for WebKit browsers */
.scroll::-webkit-scrollbar {
    width: 3px; /* Set the width of the scrollbar */
}

.scroll::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.scroll::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.scroll:active::-webkit-scrollbar-thumb,
.scroll:focus::-webkit-scrollbar-thumb,
.scroll:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

.tbody-scroll-max-min-height-220 {
    min-height: 220px; /* Set the maximum height of the element */
    max-height: 220px; /* Set the maximum height of the element */
}

.tbody-scroll-max-min-height-480 {
    min-height: 480px; /* Set the maximum height of the element */
    max-height: 480px; /* Set the maximum height of the element */
}

/* Add scrollbar to a tbody element */
.tbody-scroll {
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
    cursor: pointer;
}

/* Customize scrollbar appearance for WebKit browsers */
.tbody-scroll::-webkit-scrollbar {
    width: 5px; /* Set the width of the scrollbar */
}

.tbody-scroll::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.tbody-scroll::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.tbody-scroll:active::-webkit-scrollbar-thumb,
.tbody-scroll:focus::-webkit-scrollbar-thumb,
.tbody-scroll:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

.hide-show-column:hover .dropdown-item:hover {
    background-color: #FFFFFF;
    color: #0c1427;
}

.filter-column:hover .dropdown-item:hover {
    background-color: #FFFFFF;
    color: #0c1427;
}

.sort_arrow {
    font-weight: lighter;
    float: right;
}

.text-thin {
    color: #c2bfbf;
}

.filter-button {
    border: 1px #bdbbbb dashed;
    padding: 7px 7px;
    border-radius: 20px;
    cursor: pointer;
    margin: 2px;
    bottom: 9px;
}

.filter-button:hover {
    background-color: #ededed;
}

.dropdown-menu {
    margin-top: 8px;
}

/*Table head fixed*/

.table-fixed {
    width: 100%
}

.table-fixed > thead,
.table-fixed > tbody,
.table-fixed > thead > tr,
.table-fixed > tbody > tr,
.table-fixed > thead > tr > th,
.table-fixed > tbody > tr > td {
    display: block;
}

.table-fixed > tbody > tr:after,
.table-fixed > thead > tr:after {
    content: ' ';
    display: block;
    visibility: hidden;
    clear: both;
}

.table-fixed > tbody > tr.data-tr > td,
.table-fixed > thead > tr > td {
    float: left;
}

.hiddenRow {
    padding: 0 !important;
}

.table-fixed tbody tr.row-color {
    background-color: #eeee;
}
</style>
