<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 col-md-10 col-lg-10">
                    <div class="btn-group">
                        <button type="button" class="btn btn-gray btn-rounded"
                                id="dropdownMenuLink"
                                data-toggle="dropdown">
                            <i class="mdi mdi-eye-off"></i>
                        </button>
                        <div class="dropdown-menu hide-show-column" aria-labelledby="btnGroupDrop1"
                             style="padding: 20px">
                            <div class="dropdown-item">
                                <p>
                                    {{ __('default.you_can_show') }}
                                </p>
                            </div>
                            <hr>
                            <div class="dropdown-item scroll">
                                <div class="row pt-2">
                                    <template v-for="(column) in options.columns">
                                        <div class="col-12 d-flex justify-content-between">
                                            <p>{{ __('default.' + column.title) }}</p>
                                            <div class="material-switch pull-right">
                                                <a-switch v-model:checked="column.isVisible" size="small"
                                                          @change="getColWidth"/>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <hr>
                            <div class="dropdown-item d-flex justify-content-end">
                                <span></span>
                                <button class="btn btn-sm btn-default float-right"
                                        @click.prevent="clearColumnVisibility" type="button"> {{
                                        __('default.clear')
                                    }}
                                </button>
                            </div>
                        </div>

                        <button type="button" :title="__('default.refresh')" class="btn btn-rounded btn-gray mr-1"
                                @click.prevent="refreshTable">
                            <i class="mdi mdi-reload"></i>
                        </button>
                    </div>

                    <div class="btn-group" role="group" v-if="options.exportAble &&
                        (options.exportAble.csv || options.exportAble.excel || options.exportAble.pdf ||options.exportAble.print)">
                        <div class="dropdown show mr-1">
                            <a class="btn btn btn-gray btn-rounded"
                               href="javascript:void(0)"
                               id="dropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <i class="mdi mdi-export"></i> Export
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)" v-if="options.exportAble.csv">
                                    <i class="mdi mdi-file-excel"></i> CSV
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" v-if="options.exportAble.excel">
                                    <i class="mdi mdi-file-excel-box"></i> EXCEL
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" v-if="options.exportAble.pdf">
                                    <i class="mdi mdi-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" v-if="options.exportAble.print">
                                    <i class="mdi mdi-printer"></i> PRINT
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <div class="dropdown show mr-1" v-if="options.request.hasOwnProperty('status')">
                            <span class="filter-button">
                                <span v-if="options.request.status !== ''" class="mr-1"
                                      :class="options.request.status !== '' ? 'text-primary' : ''"
                                      @click.prevent="clearStatusFilter">
                                    <i class="mdi mdi-close-circle"></i>
                                </span>
                                <span data-toggle="dropdown">
                                    <span v-if="options.request.status !== ''"
                                          :class="options.request.status !== '' ? 'text-primary' : ''">
                                        {{ __('default.status') }} |
                                        {{ options.request.status === '1' ? 'Active' : 'In-active' }}
                                    </span>
                                    <span v-else>
                                        <i class="mdi mdi-plus-circle"></i>
                                        {{ __('default.status') }}
                                    </span>
                                </span>

                                <div class="dropdown-menu filter-column">
                                    <div class="dropdown-item">
                                        <a-form-item :label="__('default.status')">
                                            <a-radio-group v-model:value="options.request.status">
                                                <a-radio value="1">Active</a-radio>
                                                <a-radio value="0">In-active</a-radio>
                                            </a-radio-group>
                                        </a-form-item>
                                    </div>
                                </div>
                            </span>
                        </div>
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
                                        <a-form-item :label="__('default.' + filter.title)" required>
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
                        <div class="btn-group" v-if="filter.type === 'button-checkbox'">
                            <div class="dropdown show mr-1">
                                <span class="filter-button">
                                <span v-if="filter.filterValue !== ''" class="mr-1"
                                      :class="filter.filterValue !== '' ? 'text-primary' : ''"
                                      @click.prevent="clearFilter(filter_index, filter.key)">
                                    <i class="mdi mdi-close-circle"></i>
                                </span>
                                <span data-toggle="dropdown">
                                    <span v-if="filter.filterValue !== ''"
                                          :class="filter.filterValue !== '' ? 'text-primary' : ''">
                                        {{ __('default.' + filter.title) }}
                                        | {{ filter.filterValue.toUpperCase() }}
                                    </span>
                                    <span v-else>
                                        <i class="mdi mdi-plus-circle"></i>
                                        {{ __('default.' + filter.title) }}
                                    </span>
                                </span>

                                <div class="dropdown-menu filter-column">
                                    <div class="dropdown-item">
                                <a-form-item :label="__('default.' + filter.title)" required>
                                        <a-radio-group v-model:value="filter.filterValue" button-style="solid"
                                                       @change="filterList(filter.filterValue, filter.key)"
                                                       style="width: 100%">
                                            <a-radio-button v-for="(option, option_index) in filter.option"
                                                            :key="option_index"
                                                            :value="option">
                                                {{ __('default.' + option) }}
                                            </a-radio-button>
                                        </a-radio-group>
                                </a-form-item>
                                    </div>
                                </div>
                            </span>

                            </div>
                        </div>

                        <div class="btn-group" v-if="filter.type === 'date'">
                            <div class="mr-1">
                                <span class="filter-button">
                                <span v-if="filter.filterValue !== null" class="mr-1"
                                      :class="filter.filterValue !== null ? 'text-primary' : ''"
                                      @click.prevent="clearFilter(filter_index, filter.key)">
                                    <i class="mdi mdi-close-circle"></i>
                                </span>
                                <span class="daterange">
                                    <span v-if="filter.filterValue !== null"
                                          :class="filter.filterValue !== null ? 'text-primary' : ''">
                                        {{ __('default.' + filter.title) }}
                                        | {{
                                            filter.filterValue ?? ''
                                        }}
                                    </span>
                                    <span v-else>
                                        <i class="mdi mdi-plus-circle"></i>
                                        {{ __('default.' + filter.title) }}
                                    </span>
                                </span>
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
                            <search-outlined style="color: rgba(0, 0, 0, 0.45)" v-if="!options.request.search"/>
                            <a-tooltip title="Clear Search" placement="left" @click.prevent="clearSearch" v-else>
                                <close-circle-filled style="color: rgba(0, 0, 0, 0.45)"/>
                            </a-tooltip>
                        </template>
                    </a-input>
                </div>
            </div>
            <div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-fixed border">
                                <thead>
                                <tr>
                                    <template v-for="(col) in options.columns">
                                        <td v-if="col.isVisible" :width="col.width ? col.width+'%' : colWidth+'%'">
                                            <span class="font-bold pull-left">
                                                {{ __('default.' + col.title) }}
                                            </span>

                                            <template v-if="col.orderAble && col.type !== 'action'">
                                            <span @click.prevent="getOrderBy(col.key)">
                                                <span class="sort_arrow cursor-pointer"
                                                      :class="options.request.order_by === col.key && options.request.order_dir === 'desc' ? '' : 'text-thin'">
                                                    &darr;
                                               </span>
                                                <span class="sort_arrow cursor-pointer"
                                                      :class="options.request.order_by === col.key && options.request.order_dir === 'asc' ? '' : 'text-thin'">
                                                    &uarr;
                                                </span>
                                            </span>
                                            </template>
                                        </td>
                                    </template>
                                </tr>
                                </thead>
                                <tbody class="tbody-scroll"
                                       :class="windowHeight > 620 ? 'tbody-scroll-max-min-height-480' : 'tbody-scroll-max-min-height-220'">
                                <tr class="loader-tr" v-if="options.loader">
                                    <td :colspan="visibleColumn" class="text-center align-middle">
                                        <a-spin/>
                                    </td>
                                </tr>
                                <template v-else>
                                    <tr class="data-tr" v-if="options.responseData.data.length > 0"
                                        v-for="(row, row_index) in options.responseData.data"
                                        :key="row_index">
                                        <template v-for="(column) in options.columns">
                                            <td v-if="column.isVisible"
                                                :width="column.width ? column.width +'%' : colWidth +'%'">
                                                <div class="wd-95p overflow-hidden">
                                                    <template v-if="column.type === 'sl'">
                                                        {{ (parseInt(row_index) + parseInt(1)) }}
                                                    </template>
                                                    <template v-else-if="column.type === 'text'">
                                                        {{ row[column.key] }}
                                                    </template>
                                                    <template v-else-if="column.type === 'link'">
                                                        <a :href="column.url">{{ row[column.key] }}</a>
                                                    </template>
                                                    <template v-else-if="column.type === 'object'">
                                                        {{ column.modifier(row[column.key], row) }}
                                                    </template>
                                                    <template v-else-if="column.type === 'component'">
                                                        <template v-if="column.rowValues">
                                                            <component
                                                                :is="column.componentName"
                                                                :item="row"
                                                                :value="row[column.key]"
                                                            />
                                                        </template>
                                                        <template v-else>
                                                            <component
                                                                :is="column.componentName"
                                                                :value="row[column.key]"
                                                            />
                                                        </template>
                                                    </template>
                                                    <template v-else-if="column.type === 'action'">
                                                        <component
                                                            :is="column.componentName"
                                                            :row="row"
                                                            :permission="column.permission"
                                                            :value="row[column.key]"
                                                            :row_index="row_index"
                                                        />
                                                    </template>
                                                    <template v-else-if="column.type === 'custom-html'">
                                                    <span :class="column.className"
                                                        v-html="column.modifier(row[column.key], row)"></span>
                                                    </template>
                                                    <template v-else-if="column.type === 'custom-data'">
                                                        {{ column.modifier(row) }}
                                                    </template>
                                                </div>
                                            </td>
                                        </template>
                                    </tr>
                                    <tr class="loader-tr" v-else>
                                        <td :colspan="visibleColumn" class="text-center align-middle">
                                            <h3>
                                                <InboxOutlined/>
                                            </h3>
                                            No Data found.
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
                                    style="width: 70px"
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
</template>

<script>
import dayjs from 'dayjs';
import Loader from "./Loader.vue";
import {SearchOutlined, CloseCircleFilled, InboxOutlined} from '@ant-design/icons-vue';

export default {
    name: "AppTable",
    components: {Loader, SearchOutlined, CloseCircleFilled, InboxOutlined},
    props: {
        options: {
            type: Object,
            required: true
        },
        filters: {
            type: Array,
            required: false
        }
    },
    data() {
        return {
            windowHeight: window.innerHeight,
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
            filterFields: [],
            colWidth: '',
            visibleColumn: '',
            columnHistory: [],
        }
    },
    created() {
        this.storeColumnHistory()
    },
    mounted() {
        this.$nextTick(() => {
            $(".hide-show-column").click(function (e) {
                e.stopPropagation();
            })

            $(".filter-column").click(function (e) {
                e.stopPropagation();
            })

            let date_range = this.options.filters?.find(filter => filter.type === 'date');
            if (date_range){
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
                    maxYear: parseInt(moment().format('YYYY'),10)
                }, function (start, end, label) {
                    date_range.filterValue = start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD');
                });
            }
        })
        this.getColWidth();
    },
    watch: {
        options: {
            immediate: true,
            deep: true,
            handler() {
                if (this.options) {
                    this.getPages();
                    this.getTotalValue();
                    this.defaultColWidth();
                }
            },
        },
        'options.request.per_page': function () {
            this.$parent.getData()
        },
        'options.request.status': function () {
            this.page = {
                value: 1,
                label: 1
            }
            this.$parent.getData()
        },
        'options.filters':{
            immediate: true,
            deep: true,
            handler() {
                if (this.options.filters) {
                    const daterange = this.options.filters?.find(item => item.type === 'date');
                    if (daterange && daterange.filterValue){
                        this.setDateRangeValue(daterange.filterValue)
                    }
                }
            },
        },
    },
    methods: {
        defaultColWidth() {
            this.columnHistory = this.options.columns;
            const visibleColumn = this.options.columns.filter(item => item.isVisible);
            let width = parseFloat(100 / visibleColumn.length);
            this.colWidth = width;
        },
        getColWidth() {
            const visibleColumn = this.options.columns.filter(item => item.isVisible);
            if (visibleColumn.length) {
                const sum = visibleColumn.reduce((accumulator, object) => {
                    if (object?.width) {
                        return accumulator + parseInt(object.width);
                    }
                }, 0);
                if (sum <= 100) {
                    const remainingWidth = 100 - sum;
                    const adjustWidth = (remainingWidth / visibleColumn.length);
                    visibleColumn.forEach((item, itemIndex) => {
                        const oldColumnInfo = JSON.parse(localStorage.getItem('columns')).find(col_item => item.key === col_item.key)
                        if (oldColumnInfo) {
                            visibleColumn[itemIndex].width = (parseInt(oldColumnInfo.width) + parseFloat(adjustWidth))
                        }
                    })
                } else {
                    visibleColumn.forEach((item, itemIndex) => {
                        const oldColumnInfo = JSON.parse(localStorage.getItem('columns')).find(col_item => item.key === col_item.key)
                        if (oldColumnInfo) {
                            visibleColumn[itemIndex].width = (parseInt(oldColumnInfo.width))
                        }
                    })
                }

                this.visibleColumn = visibleColumn.length;
            }
        },
        setDateRangeValue(value){
            this.$parent.filterData(value, 'date')
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
        getTotalValue() {
            const data = this.options.responseData;
            this.from = data?.from;
            this.to = data?.to;
            this.total = data?.total;
        },
        async getPaginate(value) {
            const page = value
            const requestUrl = this.options.responseData?.path + '?page=' + page;
            await this.$parent.getData(requestUrl)
        },
        reAssignPage(page) {
            this.page = this.pages.find(item => parseInt(item.value) === parseInt(page))
        },
        async getPreviousPage() {
            const requestUrl = this.options.responseData?.prev_page_url;
            const page = (parseInt(this.options.responseData?.current_page) - 1) === 0 ? 1 : (parseInt(this.options.responseData?.current_page) - 1)
            this.reAssignPage(page)
            await this.$parent.getData(requestUrl)
        },
        async getNextPage() {
            const requestUrl = this.options.responseData?.next_page_url;
            const page = (parseInt(this.options.responseData?.current_page) + 1)
            this.reAssignPage(page)
            await this.$parent.getData(requestUrl)
        },
        async getFirstPage() {
            const requestUrl = this.options.responseData?.first_page_url;
            this.reAssignPage(1)
            await this.$parent.getData(requestUrl)
        },
        async getLastPage() {
            const requestUrl = this.options.responseData?.last_page_url;
            this.reAssignPage(parseInt(this.options.responseData?.last_page))
            await this.$parent.getData(requestUrl)
        },
        async searchData() {
            await this.$parent.getData()
        },
        async clearSearch() {
            this.options.request.search = '';
            await this.$parent.getData()
        },
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
                } else if (this.options.filters[filter_key].type === 'button-checkbox') {
                    this.options.filters[filter_key].filterValue = ''
                } else if (this.options.filters[filter_key].type === 'date') {
                    this.options.filters[filter_key].filterValue = null
                }
            }

            this.page = {
                value: 1,
                label: 1
            }
            await this.$parent.getData()
        },
        changePerPage(page) {
            this.page = {
                value: 1,
                label: 1
            }
            this.options.request.per_page = page
        },
        clearColumnVisibility() {
            let i;
            for (i = 0; i < this.options.columns.length; i++) {
                this.options.columns[i].isVisible = true;
            }
            this.getColWidth();
        },
        getDateFilter(date) {
            const dates = this.options.filters.find(item => item.key === 'date');
            if (date) {
                const startDate = dates.filterValue[0].format('YYYY-MM-DD');
                const endDate = dates.filterValue[1].format('YYYY-MM-DD');
                this.options.request.date = startDate + ' to ' + endDate;
                this.$parent.getData();
            } else {
                this.clearFilter(this.options.filters.indexOf(dates), 'date')
            }
        },
        clearStatusFilter() {
            if (this.options.request.status) {
                this.options.request.status = '';
                this.$parent.getData()
            }
        },
        getOrderBy(orderBy) {
            this.options.request.order_by = orderBy;
            this.options.request.order_dir = this.options.request.order_dir === 'desc' ? 'asc' : (this.options.request.order_dir === 'asc' ? 'desc' : 'asc');
            this.$parent.getData();
        },
        filterList(event, key) {
            this.page = {
                value: 1,
                label: 1
            }
            this.$parent.filterData(event, key)
        },
        clearFilter(filter_index, key) {
            if (this.options.filters[filter_index].type === 'drop-down-filter') {
                this.options.filters[filter_index].filterValue = key
                let value = this.options.filters[filter_index].filterValue
                this.$parent.filterData('', key)
            } else if (this.options.filters[filter_index].type === 'button-checkbox') {
                this.options.filters[filter_index].filterValue = ''
                this.$parent.filterData('', key)
            } else if (this.options.filters[filter_index].type === 'date') {
                this.options.filters[filter_index].filterValue = null
                this.$parent.filterData(null, key)
            }
        },
        storeColumnHistory() {
            const columnHistory = this.options.columns;
            localStorage.setItem('columns', JSON.stringify(columnHistory));
        }
    }
}
</script>

<style scoped>
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
</style>
