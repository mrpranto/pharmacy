<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-1">
                    <select style="border-radius: 5px"
                            v-model="options.request.per_page"
                            name="dataTableExample_length" aria-controls="dataTableExample"
                            class="custom-select custom-select-sm form-control">
                        <option v-for="(per_page, per_page_index) in per_pages" :value="per_page" :key="per_page_index">
                            {{ per_page }}
                        </option>
                    </select>
                </div>
                <div class="col-sm-9"></div>
                <div class="col-sm-2">
                    <a-input v-model:value="options.request.search"
                             @pressEnter="searchData"
                             placeholder="Search...">
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
                    <table class="table table-bordered text-center" v-else>
                        <thead>
                        <tr>
                            <template v-for="(col) in options.columns">
                                <th class="bg-gray" v-if="col.isVisible">
                                    {{ col.title }}
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
            <div class="row mt-3">
                <div class="col-sm-2">
                    Showing {{ from }} to {{ to }} of {{ total }} entries
                </div>
                <div class="col-sm-6 offset-4">
                    <div class="btn-toolbar mb-3 justify-content-end" role="toolbar"
                         aria-label="Toolbar with button groups">
                        <div class="input-group mr-3">
                            <a-select
                                v-model="page"
                                show-search
                                placeholder="Pages"
                                style="width: 100px;"
                                :options="pages"
                                @change="getPaginate"
                            ></a-select>

                        </div>
                        <div class="btn-group">
                            <a class="btn btn-gray btn-sm btn-icon-text btn-left-radius"
                               :class="options.responseData.current_page === 1 ? 'disabled' : ''"
                               @click.prevent="getFirstPage">
                                <i class="mdi mdi-chevron-double-left"></i> First Page
                            </a>
                            <a class="btn btn-gray btn-sm btn-icon-text"
                               :class="options.responseData.prev_page_url == null ? 'disabled' : ''"
                               @click.prevent="getPreviousPage">
                                <i class="mdi mdi-chevron-left"></i> Previous Page
                            </a>
                            <a class="btn btn-gray btn-sm btn-icon-text"
                               :class="options.responseData.next_page_url == null ? 'disabled' : ''"
                               @click.prevent="getNextPage">
                                Next Page<i class="mdi mdi-chevron-right"></i>
                            </a>
                            <a class="btn btn-gray btn-sm btn-icon-text btn-right-radius"
                               :class="options.responseData.last_page === options.responseData.current_page ? 'disabled' : ''"
                               @click.prevent="getLastPage">
                                Last Page <i class="mdi mdi-chevron-double-right"></i>
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
            page: 1,
            pages: [],
            from: '',
            to: '',
            total: '',
        }
    },
    mounted() {

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
        async getPreviousPage() {
            const requestUrl = this.options.responseData?.prev_page_url;
            this.$parent.getData(requestUrl)
        },
        async getNextPage() {
            const requestUrl = this.options.responseData?.next_page_url;
            await this.$parent.getData(requestUrl)
        },
        async getFirstPage() {
            const requestUrl = this.options.responseData?.first_page_url;
            await this.$parent.getData(requestUrl)
        },
        async getLastPage() {
            const requestUrl = this.options.responseData?.last_page_url;
            await this.$parent.getData(requestUrl)
        },
        async searchData() {
            await this.$parent.getData()
        },
        async clearSearch(){
            this.options.request.search = '';
            await this.$parent.getData()
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
</style>
