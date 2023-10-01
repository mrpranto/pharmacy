<template>
    <div class="row m-3">
        <div class="col-sm-12 col-md-7 col-lg-7">
            <div class="row">
                <div class="col-11">
                    <a-form-item required>
                        <a-input-group compact>
                            <a-select
                                style="width: 100%"
                                :size="'large'"
                                show-search
                                :placeholder="__('default.search_product')"
                                @change="">
                            </a-select>
                        </a-input-group>
                    </a-form-item>
                </div>
                <div class="col-1">
                    <button class="btn btn-primary btn-lg" style="border-radius: 5px">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="feather feather-filter">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-6">
                            <p class="text-muted">Product filter</p>
                        </div>
                        <div class="col-sm-6">
                            <a class="text-primary float-right text-decoration-none cursor-pointer">
                                Clear Filter
                            </a>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <a-form-item required>
                                <a-input-group compact>
                                    <a-select
                                        style="width: 100%"
                                        :size="'large'"
                                        show-search
                                        :placeholder="__('default.category')"
                                        @change="">
                                    </a-select>
                                </a-input-group>
                            </a-form-item>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <a-form-item required>
                                <a-input-group compact>
                                    <a-select
                                        style="width: 100%"
                                        :size="'large'"
                                        show-search
                                        :placeholder="__('default.company')"
                                        @change="">
                                    </a-select>
                                </a-input-group>
                            </a-form-item>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-5 col-lg-5">
            <div class="row">
                <div class="col-10">
                    <a-form-item required>
                        <a-input-group compact>
                            <a-select
                                style="width: 100%"
                                :size="'large'"
                                show-search
                                :placeholder="__('default.customers')"
                                @change="">
                            </a-select>
                        </a-input-group>
                    </a-form-item>
                </div>
                <div class="col-2">
                    <button class="btn btn-primary btn-lg btn-block" style="border-radius: 5px">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             class="feather feather-user-plus">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {UserAddOutlined, DownloadOutlined} from "@ant-design/icons-vue";

export default {
    name: "AddNewSale",
    components: {UserAddOutlined, DownloadOutlined}
}
</script>

<style scoped>

</style>
