<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-1">
                    <div class="btn-group" role="group">
                        <div class="dropdown show mr-1">
                            <a class="btn btn btn-gray btn-rounded"
                               href="javascript:void(0)"
                               id="dropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                {{ options.request.per_page }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="javascript:void(0)"
                                   v-for="(per_page, per_page_index) in per_pages"
                                   @click.prevent="changePerPage(per_page)"
                                   :key="per_page_index">{{ per_page }}</a>
                            </div>
                        </div>
                        <div class="dropdown show">
                            <a class="btn btn btn-gray btn-rounded"
                               href="javascript:void(0)"
                               id="dropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <i class="mdi mdi-eye-off"></i>
                            </a>
                            <div class="dropdown-menu hide-show-column" aria-labelledby="btnGroupDrop1"
                                 style="padding: 25px">
                                <div class="dropdown-item">
                                    <p>
                                        {{ __('default.you_can_show') }}
                                    </p>
                                </div>
                                <hr>
                                <div class="dropdown-item scroll">
                                    <div class="row pt-2">
                                        <template v-for="(column) in options.columns">
                                            <div class="col-12 d-flex justify-content-between"
                                                 style="padding-bottom: 0px; padding-top: 3px">
                                                <p>{{ __('default.'+column.title) }}</p>
                                                <div class="material-switch pull-right">
                                                    <a-switch v-model:checked="column.isVisible" size="small"/>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <hr>
                                <div class="dropdown-item d-flex justify-content-end">
                                    <span></span>
                                    <button class="btn btn-sm btn-default float-right"
                                            @click.prevent="clearColumnVisibility" type="button"> {{ __('default.clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="btn-group" role="group" aria-label="Basic example" v-if="options.exportAble">
                        <button type="button" class="btn btn btn-rounded btn-gray" v-if="options.exportAble.csv">
                            <i class="mdi mdi-file-excel"></i> CSV
                        </button>
                        <button type="button" class="btn btn btn-gray" v-if="options.exportAble.excel">
                            <i class="mdi mdi-file-excel-box"></i> EXCEL
                        </button>
                        <button type="button" class="btn btn btn-gray" v-if="options.exportAble.pdf">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button>
                        <button type="button" class="btn btn btn-gray" v-if="options.exportAble.print">
                            <i class="mdi mdi-printer"></i> PRINT
                        </button>
                        <button type="button" class="btn btn btn-rounded btn-gray" @click.prevent="refreshTable">
                            <i class="mdi mdi-reload"></i> REFRESH
                        </button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <a-input v-model:value="options.request.search"
                             style="border-radius: 20px"
                             @pressEnter="searchData"
                             :placeholder="__('default.search')+'...'">
                        <template #suffix>
                            <search-outlined style="color: rgba(0, 0, 0, 0.45)" v-if="!options.request.search"/>
                            <a-tooltip title="Clear Search" @click.prevent="clearSearch" v-else>
                                <close-circle-outlined style="color: rgba(0, 0, 0, 0.45)"/>
                            </a-tooltip>
                        </template>
                    </a-input>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-12">
                    <Loader v-if="options.loader"/>
                    <div class="table-responsive" v-else>
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <template v-for="(col) in options.columns">
                                    <th class="bg-gray" v-if="col.isVisible">
                                        {{ __('default.'+col.title) }}
                                    </th>
                                </template>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, row_index) in options.responseData.data" :key="row_index">
                                <template v-for="(column) in options.columns">
                                    <td v-if="column.isVisible">
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
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-2">
                    {{ __('default.showing') }} {{ from }} {{ __('default.to') }} {{ to }} {{ __('default.of') }} {{ total }} {{ __('default.entries') }}
                </div>
                <div class="col-sm-6 offset-4">
                    <div class="btn-toolbar mb-3 justify-content-end" role="toolbar"
                         aria-label="Toolbar with button groups">
                        <div class="input-group mr-3">
                            <a-select
                                v-model:value="page"
                                show-search
                                placeholder="Pages"
                                style="width: 100px;"
                                :options="pages"
                                @change="getPaginate"
                            ></a-select>

                        </div>
                        <div class="btn-group">
                            <a class="btn btn-gray btn btn-icon-text btn-left-radius"
                               :class="options.responseData.current_page === 1 ? 'disabled' : ''"
                               @click.prevent="getFirstPage">
                                <i class="mdi mdi-chevron-double-left"></i> {{ __('default.first_page') }}
                            </a>
                            <a class="btn btn-gray btn btn-icon-text"
                               :class="options.responseData.prev_page_url == null ? 'disabled' : ''"
                               @click.prevent="getPreviousPage">
                                <i class="mdi mdi-chevron-left"></i> {{ __('default.previous_page') }}
                            </a>
                            <a class="btn btn-gray btn btn-icon-text"
                               :class="options.responseData.next_page_url == null ? 'disabled' : ''"
                               @click.prevent="getNextPage">
                                {{ __('default.next_page') }} <i class="mdi mdi-chevron-right"></i>
                            </a>
                            <a class="btn btn-gray btn btn-icon-text btn-right-radius"
                               :class="options.responseData.last_page === options.responseData.current_page ? 'disabled' : ''"
                               @click.prevent="getLastPage">
                                {{ __('default.last_page') }} <i class="mdi mdi-chevron-double-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loader from "./Loader.vue";
import {SearchOutlined, CloseCircleOutlined} from '@ant-design/icons-vue';

export default {
    name: "AppTable",
    components: {Loader, SearchOutlined, CloseCircleOutlined},
    props: {
        options: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
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
        }
    },
    mounted() {
        this.$nextTick(() => {
            $(".hide-show-column").click(function (e) {
                e.stopPropagation();
            })
        })
    },
    watch: {
        options: {
            immediate: true,
            deep: true,
            handler() {
                if (this.options) {
                    this.getPages();
                    this.getTotalValue()
                }
            },
        },
        'options.request.per_page': function () {
            this.$parent.getData()
        }
    },
    methods: {
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
            await this.$parent.getData()
        },
        changePerPage(page) {
            this.options.request.per_page = page
        },
        clearColumnVisibility() {
            let i;
            for (i = 0; i < this.options.columns.length; i++) {
                this.options.columns[i].isVisible = true;
            }
        }
    }
}
</script>

<style scoped>
.bg-gray {
    background-color: #f2f4f9;
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

.hide-show-column:hover .dropdown-item:hover {
    background-color: #FFFFFF;
    color: #0c1427;
}
</style>
