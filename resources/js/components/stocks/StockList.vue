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
                                <a-spin v-if="options.loader"/>
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




                                        <template v-for="(filter, filter_index) in options.filters">
                                            <div class="btn-group" v-if="filter.type === 'drop-down-filter'">
                                                <div class="dropdown show mr-1">
                                                    <span class="filter-button">
                                                        <span v-if="filter.filterValue !== filter.key" class="mr-1"
                                                              :class="filter.filterValue !== filter.key ? 'text-primary' : ''"
                                                              @click.prevent="clearFilter(filter_index, filter.key)">
                                                            <i class="mdi mdi-close-circle"></i>
                                                        </span>
                                                        <span data-toggle="dropdown">
                                                            <span v-if="filter.filterValue !== filter.key"
                                                                  :class="filter.filterValue !== filter.key ? 'text-primary' : ''">
                                                                {{
                                                                    __('default.' + filter.title)
                                                                }} | {{ filter.option.find(item => item.value === filter.filterValue).label }}
                                                            </span>
                                                            <span v-else>
                                                                <i class="mdi mdi-plus-circle"></i>
                                                                {{ __('default.' + filter.title) }}
                                                            </span>
                                                        </span>

                                                        <div class="dropdown-menu filter-column">
                                                            <div class="dropdown-item">
                                                                <a-form-item :label="__('default.' + filter.title)">
                                                                    <a-select
                                                                        v-model:value="filter.filterValue"
                                                                        class="filter-select"
                                                                        style="min-width: 200px; max-width: 100%"
                                                                        show-search
                                                                        :placeholder="__('default.' + filter.title)"
                                                                        :options="filter.option"
                                                                        :filter-option="filter.filterOption"
                                                                        @change="filterList($event, filter.key)"
                                                                    ></a-select>
                                                                </a-form-item>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </template>




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
                                            <td width="20%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.product') }}
                                                 </span>
                                            </td>
                                            <td width="15%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.category') }}
                                                 </span>
                                            </td>
                                            <td width="20%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.company') }}
                                                 </span>
                                            </td>
                                            <td width="15%">
                                                 <span class="font-bold pull-left">
                                                {{ __('default.unit') }}
                                                 </span>
                                            </td>
                                            <td width="10%" class="text-center">
                                                 <span class="font-bold">
                                                {{ __('default.total_different_qty') }}
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
                                        <tbody class="tbody-scroll"
                                               :class="windowHeight > 620 ? 'tbody-scroll-max-min-height-480' : 'tbody-scroll-max-min-height-220'">
                                            <template v-for="(product, product_index) in options.responseData.data">
                                                <tr class="data-tr" :class="isEven(product_index) ? 'row-color' : ''">
                                                    <td width="20%">
                                                        <div class="d-flex justify-content-between accordion-toggle" data-toggle="collapse"
                                                             :data-target="'#stock'+product_index">
                                                            <div class="d-flex align-items-center">

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

                                                                    <div class="status online" v-if="product.status == '1'"></div>
                                                                    <div class="status offline" v-else></div>
                                                                </figure>
                                                                <div>
                                                                    <p class="font-weight-bolder text-capital">{{ product.name.toUpperCase() }}</p>
                                                                    <p class="text-muted tx-13"><b>{{ __('default.barcode') }}:</b> {{ product?.barcode }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td width="15%">{{ product.category.name }}</td>
                                                    <td width="20%">{{ product.company.name }}</td>
                                                    <td width="15%">{{ product.unit.name }} ({{ product.unit.pack_size }})</td>
                                                    <td width="10%" class="text-center">
                                                        <span class="font-bold">
                                                            {{ product.stocks.length }}
                                                        </span>
                                                    </td>
                                                    <td width="12%" class="text-center">
                                                        <span class="font-bold">
                                                            {{ totalQty(product.stocks, 'available_quantity') }}
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
                                                                                        <p style="font-size: 18px">
                                                                                            <b>{{ product.name.toUpperCase() }}</b> have {{ product.stocks.length }} different types of stock.
                                                                                        </p>
                                                                                        <p class="tx-14 text-muted">
                                                                                            <span class="mr-3"><b>{{ __('default.total_purchase_qty') }} : </b> {{ totalQty(product.stocks, 'purchase_quantity') }},</span>
                                                                                            <span class="mr-3"><b>{{ __('default.total_sale_qty') }} : </b> {{ totalQty(product.stocks, 'sale_quantity') }},</span>
                                                                                            <span class="mr-3"><b>{{ __('default.total_available_qty') }} : </b> {{ totalQty(product.stocks, 'available_quantity') }}</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-striped table-bordered border">
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th class="text-center">{{ __('default.unit_price') }}</th>
                                                                                        <th class="text-center">{{ __('default.sale_price') }}</th>
                                                                                        <th class="text-center">{{ __('default.discount') }}</th>
                                                                                        <th class="text-center">{{ __('default.purchase_quantity') }}</th>
                                                                                        <th class="text-center">{{ __('default.sale_quantity') }}</th>
                                                                                        <th class="text-center">{{ __('default.available_quantity') }}</th>
                                                                                    </tr>

                                                                                    <tr v-for="(stock, stock_index) in product.stocks" :key="stock_index"  :class="isEven(stock_index) ? 'row-color' : ''">
                                                                                        <th class="text-center">{{ (stock_index+1) }}</th>
                                                                                        <td class="text-center">{{ $showCurrency(stock.unit_price) }}</td>
                                                                                        <td class="text-center">{{ $showCurrency(stock.sale_price) }}</td>
                                                                                        <td class="text-center">{{ stock.discount }} {{ stock.discount_type }}</td>
                                                                                        <td class="text-center">{{ stock.purchase_quantity }}</td>
                                                                                        <td class="text-center">{{ stock.sale_quantity }}</td>
                                                                                        <td class="text-center">{{ stock.available_quantity }}</td>
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
            windowHeight: window.innerHeight,
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
            total: '',
            dataArray:[],

            options: {
                loader: false,
                responseData: {},
                total: 0,
                request: {
                    per_page: this.$general_setting.pagination,
                    search: '',
                    order_by: 'id',
                    order_dir: 'desc',
                    category: '',
                    company: '',
                    unit: '',
                },
                exportAble: {},
                filters: [
                    {
                        title: 'category',
                        type: "drop-down-filter",
                        key: "category",
                        filterValue: 'category',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'company',
                        type: "drop-down-filter",
                        key: "company",
                        filterValue: 'company',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                    {
                        title: 'unit',
                        type: "drop-down-filter",
                        key: "unit",
                        filterValue: 'unit',
                        option: [],
                        filterOption: this.selectFilterOption
                    },
                ],
            },
        }
    },
    created() {
        this.getData()
        this.getDependency()
    },
    mounted() {
        this.$nextTick(() => {
            $(".filter-column").click(function (e) {
                e.stopPropagation();
            })
        })
    },
    watch:{
        'options.request.category': function () {
            this.getData()
        },
        'options.request.company': function () {
            this.getData()
        },
        'options.request.unit': function () {
            this.getData()
        },
        'options.request.per_page': function () {
            this.getData()
        }
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
                    this.getTotalValue()
                    this.getPages()
                })
                .catch(err => {
                    console.error(err)
                })
        },
        async getDependency() {
            await axios.get('/product/get-dependency')
                .then(response => {
                    this.options.filters[0].option = response.data.categories.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    this.options.filters[1].option = response.data.companies.map(item => {
                        return {
                            value: item.id,
                            label: item.name
                        }
                    });
                    this.options.filters[2].option = response.data.units.map(item => {
                        return {
                            value: item.id,
                            label: item.name + ` (${item.pack_size})`
                        }
                    });

                })
                .catch(err => {
                    console.error(err)
                })
        },
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
        async searchData(){
            await this.getData()
        },
        async clearSearch() {
            this.options.request.search = '';
            await this.getData()
        },
        totalQty(stocks, column){
            return stocks.reduce((accumulator, object) => {
                return accumulator + object[column];
            }, 0);
        },
        filterList(event, key) {
            this.page = {
                value: 1,
                label: 1
            }
            this.filterData(event, key)
        },
        clearFilter(filter_index, key) {
            if (this.options.filters[filter_index].type === 'drop-down-filter') {
                this.options.filters[filter_index].filterValue = key
                let value = this.options.filters[filter_index].filterValue
                this.filterData('', key)
            }
        },
        filterData(filterValue, filterType) {
            if (filterType === 'category') {
                this.options.request.category = filterValue
            } else if (filterType === 'company') {
                this.options.request.company = filterValue
            } else if (filterType === 'unit') {
                this.options.request.unit = filterValue
            }
        },

        /*
        * Pagination function
        */
        async refreshTable() {
            for (const key in this.options.request) {
                if (key !== 'per_page') {
                    this.options.request[key] = ""
                }
            }

            let filter_key;

            for (filter_key = 0; filter_key < this.options?.filters?.length; filter_key++) {
                if (this.options.filters[filter_key].type === 'drop-down-filter') {
                    this.options.filters[filter_key].filterValue = this.options.filters[filter_key].key;
                }
            }

            this.page = {
                value: 1,
                label: 1
            }
            await this.getData()
        },
        getTotalValue() {
            const data = this.options.responseData;
            this.from = data?.from;
            this.to = data?.to;
            this.total = data?.total;
        },
        changePerPage(page) {
            this.page = {
                value: 1,
                label: 1
            }
            this.options.request.per_page = page
        },
        reAssignPage(page) {
            this.page = this.pages.find(item => parseInt(item.value) === parseInt(page))
        },
        async getPaginate(value) {
            const page = value
            const requestUrl = this.options.responseData?.path + '?page=' + page;
            await this.getData(requestUrl)
        },
        getPages() {
            const last_page = this.options.responseData?.last_page
            this.pages = [];
            for (let page = 1; page <= last_page; page++) {
                this.pages.push({
                    value: page,
                    label: page
                })
            }
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
