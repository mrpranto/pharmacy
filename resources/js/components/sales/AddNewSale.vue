<template>
    <div class="row m-3">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="row mb-3">
                <div class="col-10">
                    <a-input size="large" :placeholder="__('default.search_sale_product')" />
                </div>
                <div class="col-2">
                    <button class="btn btn-primary btn-block btn-lg" style="border-radius: 5px"
                            @click.prevent="formState.showFilterArea = true">
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

            <div class="card" v-if="formState.showFilterArea">
                <div class="card-body">
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-6">
                            <p class="text-muted h6">Product filter</p>
                        </div>
                        <div class="col-sm-6">
                            <a class="text-primary float-right text-decoration-none cursor-pointer"
                               @click.prevent="formState.showFilterArea = false">
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

            <div class="row mt-3 products-area mr-0"
                 :class="windowHeight > 620 ? 'products-area-height-700' : 'products-area-height-400'">
                <div class="col-sm-12 col-md-4 col-lg-4 mb-3 cursor-pointer" v-for="item in 400">
                    <div class="card">
                        <div class="card-body product-area">
                            <div class="text-center mb-2">
                                <div class="badge badge-success btn-block"><i class="mdi mdi-check-circle"></i> Already
                                    in cart.
                                </div>
                            </div>
                            <div class="media d-block d-sm-flex flex-column align-items-center">
                                <div class="d-flex align-items-center">
                                    <figure class="mb-0 mr-3">
                                        <a-image :width="80"
                                                 :src="'/images/medicine.png'"
                                                 class="img-sm img-thumbnail rounded-circle"
                                                 :alt="'Product name'"/>
                                        <div class="status online"></div>
                                    </figure>
                                </div>

                                <div class="media-body">
                                    <p class="font-weight-bolder mt-3 mb-1">{{ 'Product name Seclo 20mg' }}</p>
                                    <p>
                                        <span class="font-weight-bold">{{
                                                __('default.barcode')
                                            }} : </span>{{ '110002010' }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.company')
                                            }} : </span>{{ 'Beximco Pharmacitucals' }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.category')
                                            }} : </span>{{ 'Capsoule' }} <br>
                                        <span class="font-weight-bold">{{
                                                __('default.unit')
                                            }} : </span>{{ 'Box (10x10)' }} <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6">
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
                        <div class="labels col-12 row border-bottom no-gutters py-2 mb-0">
                            <b class="col-5 text-muted text-center">{{ __('default.selected_product') }}</b>
                            <b class="col-2 text-muted text-center">{{ __('default.price') }}</b>
                            <b class="col-2 text-muted text-center">{{ __('default.quantity') }}</b>
                            <b class="col-2 text-muted text-center">{{ __('default.discount') }}</b>
                            <b class="col-1 text-muted text-right"></b>
                        </div>

                        <div class="col-12 cart-area">
                            <template v-for="item in 10">
                                <div class="row border-bottom py-2 mb-0">
                                    <div class="col-5 align-middle">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <svg v-if="formState.showDiscountArea === false"
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-down cursor-pointer"
                                                 @click.prevent="formState.showDiscountArea = true">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>

                                            <svg v-else
                                                 xmlns="http://www.w3.org/2000/svg"
                                                 width="24" height="24"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-up cursor-pointer"
                                                 @click.prevent="formState.showDiscountArea = false">
                                                <polyline points="18 15 12 9 6 15"></polyline>
                                            </svg>


                                            <p class="ml-3">
                                                Product name seclo 20mg
                                                <br>
                                                <small><span class="font-weight-bolder">Barcode :</span> 011010101010,
                                                </small>
                                                <br>
                                                <small><span class="font-weight-bolder">Unit :</span> Box (10x10)
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center pt-3">
                                         <span>120000</span>
                                    </div>
                                    <div class="col-2 text-center pt-3">
                                        <a-input-number value="20000" size="small" style="width: 100%;" :min="1"
                                                        :max="100000"/>
                                    </div>
                                    <div class="col-2 text-center pt-3">
                                         <span>130000</span>
                                    </div>
                                    <div class="col-1 text-center pt-3">
                                        <i class="mdi mdi-close-circle h5 cursor-pointer"></i>
                                    </div>

                                    <div class="col-12 pt-3" v-if="formState.showDiscountArea === true">
                                        <a-form :layout="'inline'">
                                            <a-form-item :label="__('default.sale_price')">
                                                <a-input-number value="20000"
                                                                style="width: 98px"
                                                                :min="1"
                                                                size="small"
                                                                :max="100000"/>
                                            </a-form-item>
                                            <a-form-item :label="__('default.discount')">
                                                <a-input-number value="20000"
                                                                style="width: 98px"
                                                                :min="1"
                                                                size="small"
                                                                :max="100000"/>
                                            </a-form-item>
                                            <a-form-item :label="__('default.discount_type')">
                                                <a-radio-group v-model:value="formState.layout" size="small" button-style="solid">
                                                    <a-radio-button value="$">{{ $currency_symbol }}</a-radio-button>
                                                    <a-radio-button value="%">%</a-radio-button>
                                                </a-radio-group>
                                            </a-form-item>
                                        </a-form>
                                    </div>
                                </div>
                            </template>
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
    components: {UserAddOutlined, DownloadOutlined},
    data() {
        return {
            windowHeight: window.innerHeight,
            formState: {
                showFilterArea: false,
                showDiscountArea: false
            }
        }
    }
}
</script>

<style scoped>
.product-area:hover {
    box-shadow: 0.3em 0.3em 1em rgba(0, 0, 0, 0.3);
}

/* Add scrollbar to a tbody element */
.products-area-height-700{
    min-height: 700px;
    max-height: 700px;
}
.products-area-height-400{
    min-height: 400px;
    max-height: 400px;
}
.products-area {
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
    cursor: pointer;
    border-bottom: 1px #ededed solid;
}

/* Customize scrollbar appearance for WebKit browsers */
.products-area::-webkit-scrollbar {
    width: 8px; /* Set the width of the scrollbar */
}

.products-area::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.products-area::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.products-area:active::-webkit-scrollbar-thumb,
.products-area:focus::-webkit-scrollbar-thumb,
.products-area:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

/* Add scrollbar to a tbody element */
.cart-area {
    overflow-y: auto; /* Enable vertical scrolling */
    scrollbar-width: thin; /* Set the width of the scrollbar */
    scrollbar-color: #b4b1b1 #ededed; /* Set the color of the scrollbar thumb and track */
    cursor: pointer;
    min-height: 300px;
    max-height: 300px;
    border-bottom: 1px #ededed solid;
}


/* Customize scrollbar appearance for WebKit browsers */
.cart-area::-webkit-scrollbar {
    width: 8px; /* Set the width of the scrollbar */
}

.cart-area::-webkit-scrollbar-track {
    background-color: #ededed; /* Set the color of the scrollbar track */
}

.cart-area::-webkit-scrollbar-thumb {
    background-color: #b4b1b1; /* Set the color of the scrollbar thumb */
    border-radius: 50px;
}

.cart-area:active::-webkit-scrollbar-thumb,
.cart-area:focus::-webkit-scrollbar-thumb,
.cart-area:hover::-webkit-scrollbar-thumb {
    visibility: visible;
}

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
    width: 15px;
    height: 15px;
    background: #7987a1;
    position: absolute;
    bottom: 0px;
    right: 5px;
    border-radius: 50%;
    border: 2px solid #fff;
}

</style>
