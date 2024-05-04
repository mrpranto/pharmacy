
<template>
    <div>

        <a-modal v-model:open="show.openAddOpeningStock"
                 width="70%"
                 style="top: 10px"
                 :title="__('default.add_opening_stock')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }">

            <hr>


            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <div class="media d-block d-sm-flex">
                        <div class="d-flex align-items-center">
                            <figure class="mb-0 mr-2">
                                <template v-if="show.product?.product_photo">
                                    <a-image
                                        :width="75"
                                        :height="75"
                                        :src="show.product?.product_photo?.full_url"
                                        class="img-sm rounded-circle"
                                        :alt="show.product?.name"
                                    />
                                </template>
                                <template v-else>
                                    <a-image
                                        :width="75"
                                        :height="75"
                                        src="/images/medicine.png"
                                        class="img-sm rounded-circle"
                                        :alt="show.product?.name"
                                    />
                                </template>
                                <div class="status online"></div>
                            </figure>
                        </div>

                        <div class="media-body">
                            <h4 class="mt-0 mb-1">{{ show.product.name.toUpperCase() }}</h4>
                            <p>
                                <span class="font-weight-bolder">{{
                                        __('default.barcode')
                                    }} : </span>{{ show.product.barcode }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.company')
                                    }} : </span>{{ show.product.company.name }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.category')
                                    }} : </span>{{ show.product.category.name }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.unit')
                                    }} : </span>{{ show.product.unit.name }} <br>
                                <span class="font-weight-bolder">{{
                                        __('default.variant')
                                    }} : </span>{{ show.product.variant_order }} <br>
                                <span class="font-weight-bolder">{{ __('default.purchase_type') }} : </span>
                                <span class="badge badge-info" v-if="show.product.purchase_type === '%'">
                                    (%) Percentage
                                </span>
                                <span class="badge badge-success" v-else>
                                    ({{ $currency_symbol }}) Direct Price
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td><b>{{ __('default.sl') }}</b></td>
                            <td class="text-center">
                                <b class="mb-5">{{ __('default.name') }}</b>
                                <br>
                                <template v-for="(variant, variant_index) in Object.keys(show.product.variant_order)">
                                    {{ variant_index > 0 ? '/'+show.product.variant_order[variant] : show.product.variant_order[variant] }}
                                </template>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.quantity') }}
                                    </b>
                                    <a-tooltip v-if="showBulkQty == false"
                                               @click.prevent="showBulkQty = true"
                                               :title="__('default.add_bulk') +' '+__('default.quantity')"
                                               class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkQty = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkQty" id="quantity" v-model:value="bulk_quantity"
                                                :placeholder="__('default.add_bulk') +' '+__('default.quantity')"
                                                style="width: 100%"/>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.mrp') }} ({{ $currency_symbol }})
                                    </b>
                                    <a-tooltip  v-if="showBulkMrp == false"
                                                @click.prevent="showBulkMrp = true"
                                                :title="__('default.add_bulk') +' '+__('default.mrp')" class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkMrp = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkMrp"
                                                id="mrp"
                                                v-model:value="bulk_mrp"
                                                :placeholder="__('default.add_bulk') +' '+__('default.mrp')"
                                                :prefix="$currency_symbol"
                                                style="width: 100%"/>
                            </td>
                            <td v-if="show.product.purchase_type === '%'">
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.unit_percentage') }} (%)
                                    </b>
                                    <a-tooltip  v-if="showBulkUnitCostPercentage == false"
                                                @click.prevent="showBulkUnitCostPercentage = true"
                                                :title="__('default.add_bulk') +' '+__('default.unit_percentage')" class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkUnitCostPercentage = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkUnitCostPercentage"
                                                id="unit_percentage"
                                                v-model:value="bulk_unit_percentage"
                                                :placeholder="__('default.add_bulk') +' '+__('default.unit_percentage')"
                                                prefix="%"
                                                style="width: 100%"/>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.unit_price') }} ({{ $currency_symbol }})
                                    </b>
                                    <a-tooltip  v-if="showBulkUnitCost == false"
                                                @click.prevent="showBulkUnitCost = true"
                                                :title="__('default.add_bulk') +' '+__('default.unit_price')" class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkUnitCost = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkUnitCost"
                                                id="unit_price"
                                                v-model:value="bulk_unit_price"
                                                :placeholder="__('default.add_bulk') +' '+__('default.unit_price')"
                                                :prefix="$currency_symbol"
                                                style="width: 100%"/>
                            </td>
                            <td v-if="show.product.purchase_type === '%'">
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.sale_percentage') }} (%)
                                    </b>
                                    <a-tooltip  v-if="showBulkUnitSalePercentage == false"
                                                @click.prevent="showBulkUnitSalePercentage = true"
                                                :title="__('default.add_bulk') +' '+__('default.sale_percentage')" class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkUnitSalePercentage = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkUnitSalePercentage"
                                                id="sale_percentage"
                                                v-model:value="bulk_sale_percentage"
                                                :placeholder="__('default.add_bulk') +' '+__('default.sale_percentage')"
                                                prefix="%"
                                                style="width: 100%"/>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between mb-2">
                                    <b>
                                        {{ __('default.sale_price') }} ({{ $currency_symbol }})
                                    </b>
                                    <a-tooltip  v-if="showBulkUnitSalePrice == false"
                                                @click.prevent="showBulkUnitSalePrice = true"
                                                :title="__('default.add_bulk') +' '+__('default.sale_price')" class="mr-1" >
                                        <FormOutlined class="color-primary"/>
                                    </a-tooltip>

                                    <a-tooltip v-else @click.prevent="showBulkUnitSalePrice = false" :title="__('default.close')" class="mr-1" >
                                        <CloseSquareOutlined  class="color-primary"/>
                                    </a-tooltip>
                                </div>
                                <a-input-number v-if="showBulkUnitSalePrice"
                                                id="sale_price"
                                                v-model:value="bulk_sale_price"
                                                :placeholder="__('default.add_bulk') +' '+__('default.sale_price')"
                                                :prefix="$currency_symbol"
                                                style="width: 100%"/>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="this.attributeDetails.length > 1">
                                <tr v-for="(attribute, attribute_index) in this.attributeDetails" :key="attribute_index">
                                    <td>{{ (attribute_index+1) }}</td>
                                    <td class="text-center">
                                        <b class="mb-1">{{ show.product.name.toUpperCase() }}</b>
                                        <br>
                                        <p>
                                            <template v-for="(variant, variant_index) in Object.keys(show.product.variant_order)">
                                                {{ variant_index > 0 ? '/'+attribute[show.product.variant_order[variant]] : attribute[show.product.variant_order[variant]] }}
                                            </template>
                                        </p>
                                    </td>
                                    <td>
                                        <a-input-number id="quantity"
                                                        v-model:value="attribute.quantity"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="mrp"
                                                        v-model:value="attribute.mrp"
                                                        @keyup.prevent="calculateWithMrp(attribute_index)"
                                                        :prefix="$currency_symbol"
                                                        style="width: 100%"/>
                                    </td>
                                    <td v-if="show.product.purchase_type === '%'">
                                        <a-input-number id="unit_percentage"
                                                        v-model:value="attribute.unit_percentage"
                                                        @keyup.prevent="calculateUnitPrice(attribute_index)"
                                                        prefix="%"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="unit_price"
                                                        v-model:value="attribute.unit_price"
                                                        @keyup.prevent="calculateUnitPercentage(attribute_index)"
                                                        :prefix="$currency_symbol"
                                                        style="width: 100%"/>
                                    </td>
                                    <td v-if="show.product.purchase_type === '%'">
                                        <a-input-number id="sale_percentage"
                                                        v-model:value="attribute.sale_percentage"
                                                        @keyup.prevent="calculateSalePrice(attribute_index)"
                                                        prefix="%"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="sale_price"
                                                        v-model:value="attribute.sale_price"
                                                        @keyup.prevent="calculateSalePercentage(attribute_index)"
                                                        :prefix="$currency_symbol"
                                                        style="width: 100%"/>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-for="(attribute, attribute_index) in this.attributeDetails" :key="attribute_index">
                                    <td>{{ (attribute_index+1) }}</td>
                                    <td>{{ show.product.name.toUpperCase() }}</td>
                                    <td>
                                        <a-input-number id="quantity"
                                                        v-model:value="attribute.quantity"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="mrp"
                                                        v-model:value="attribute.mrp"
                                                        :prefix="$currency_symbol"
                                                         @keyup.prevent="calculateWithMrp(attribute_index)"
                                                        style="width: 100%"/>
                                    </td>
                                     <td v-if="show.product.purchase_type === '%'">
                                        <a-input-number id="unit_percentage"
                                                        v-model:value="attribute.unit_percentage"
                                                         @keyup.prevent="calculateUnitPrice(attribute_index)"
                                                        prefix="%"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="unit_price"
                                                        v-model:value="attribute.unit_price"
                                                         @keyup.prevent="calculateUnitPercentage(attribute_index)"
                                                        :prefix="$currency_symbol"
                                                        style="width: 100%"/>
                                    </td>
                                    <td v-if="show.product.purchase_type === '%'">
                                        <a-input-number id="sale_percentage"
                                                        v-model:value="attribute.sale_percentage"
                                                         @keyup.prevent="calculateSalePrice(attribute_index)"
                                                        prefix="%"
                                                        style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="sale_price"
                                                        v-model:value="attribute.sale_price"
                                                         @keyup.prevent="calculateSalePercentage(attribute_index)"
                                                        :prefix="$currency_symbol"
                                                        style="width: 100%"/>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <template #footer>
                <a-button key="back" @click="closeOpeningStock">{{ __('default.close') }}</a-button>
                <a-button v-if="loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else key="submit" type="primary" :loading="loading" @click="saveOpeningStock">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>

        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined, CloseSquareOutlined} from '@ant-design/icons-vue';
export default {
    name: "AddOpeningStockForm",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined, CloseSquareOutlined},
    props:['show'],
    data(){
        return {
            formData: {},
            attributeDetails: [],

            loading: false,
            bulk_quantity: null,
            bulk_mrp: null,
            bulk_unit_price: null,
            bulk_unit_percentage: null,
            bulk_sale_price: null,
            bulk_sale_percentage: null,

            showBulkQty: false,
            showBulkMrp: false,
            showBulkUnitCost: false,
            showBulkUnitCostPercentage: false,
            showBulkUnitSalePrice: false,
            showBulkUnitSalePercentage: false,
        }
    },
    watch: {
        show: {
            immediate: true,
            deep: true,
            handler() {
                if (this.show) {
                    this.bulk_quantity = null;
                    this.bulk_mrp = null;
                    this.bulk_unit_price = null;
                    this.bulk_unit_percentage = null;
                    this.bulk_sale_price = null;
                    this.bulk_sale_percentage = null;
                    this.showBulkQty = false;
                    this.showBulkMrp = false;
                    this.showBulkUnitCost = false;
                    this.showBulkUnitCostPercentage = false;
                    this.showBulkUnitSalePrice = false;
                    this.showBulkUnitSalePercentage = false;
                    this.combineAttributes();
                }
            },
        },
        'bulk_quantity': function (){
            this.attributeDetails.map(item => item.quantity = this.bulk_quantity)
        },
        'bulk_mrp': function (){
            this.attributeDetails.map(item => item.mrp = this.bulk_mrp);
            this.calculateBulkUnitPercentage();
            this.calculateBulkSalePercentage();
            this.calculateBulkUnitPrice();
            this.calculateBulkSalePrice();
        },
        'bulk_unit_price': function (){
            this.attributeDetails.map(item => item.unit_price = this.bulk_unit_price)
            this.calculateBulkUnitPercentage();
        },
        'bulk_unit_percentage': function (){
            this.attributeDetails.map(item => item.unit_percentage = this.bulk_unit_percentage)
            this.calculateBulkUnitPrice();
        },
        'bulk_sale_price': function (){
            this.attributeDetails.map(item => item.sale_price = this.bulk_sale_price)
            this.calculateBulkSalePercentage();
        },
        'bulk_sale_percentage': function (){
            this.attributeDetails.map(item => item.sale_percentage = this.bulk_sale_percentage)
            this.calculateBulkSalePrice();
        },
    },
    methods:{
        combineAttributes(){
            const requestAttributes = this.show.product.attributes;
            if (requestAttributes){
                this.attributeDetails = this.$attributeCombine(requestAttributes);
                return this.attributeDetails;
            }
        },
        closeOpeningStock(){
            this.show.openAddOpeningStock = false
        },
        calculateWithMrp(index){
            this.calculateUnitPercentage(index);
            this.calculateSalePercentage(index);
        },
        calculateBulkUnitPercentage(){
            this.attributeDetails.map(function (item){
                const mrp = item.mrp;
                const unit_price = item.unit_price;

                if (mrp && unit_price){
                    let cal_unit_percentage = (((mrp - unit_price)/ mrp) * 100);
                    item.unit_percentage = cal_unit_percentage.toFixed(2)
                }
            });
        },
        calculateBulkSalePercentage(){
            this.attributeDetails.map(function (item){
                const mrp = item.mrp;
                const sale_price = item.sale_price;

                if (mrp && sale_price){
                    let cal_sale_percentage = (((mrp - sale_price)/ mrp) * 100);
                    item.sale_percentage = cal_sale_percentage.toFixed(2)
                }
            });
        },
        calculateBulkUnitPrice(){
            this.attributeDetails.map(function (item){
                const mrp = item.mrp;
                const unit_percentage = item.unit_percentage;
                if (mrp && unit_percentage){
                    let cal_unit_price = (mrp - ((mrp * unit_percentage) / 100));
                    item.unit_price = cal_unit_price.toFixed(2)
                }
            });
        },
        calculateBulkSalePrice(){
            this.attributeDetails.map(function (item){
                const mrp = item.mrp;
                const sale_percentage = item.sale_percentage;
                if (mrp && sale_percentage){
                    let cal_sale_price = (mrp - ((mrp * sale_percentage) / 100));
                    item.sale_price = cal_sale_price.toFixed(2)
                }
            });
        },


        calculateUnitPercentage(index){
            const currentAttribute = this.attributeDetails[index];
            const mrp = currentAttribute.mrp;
            const unit_price = currentAttribute.unit_price;

            if (mrp && unit_price){
                let cal_unit_percentage = (((mrp - unit_price)/ mrp) * 100);
                currentAttribute.unit_percentage = cal_unit_percentage.toFixed(2)
            }
        },
        calculateSalePercentage(index){
            const currentAttribute = this.attributeDetails[index];
            const mrp = currentAttribute.mrp;
            const sale_price = currentAttribute.sale_price;

            if (mrp && sale_price){
                let cal_sale_percentage = (((mrp - sale_price)/ mrp) * 100);
                currentAttribute.sale_percentage = cal_sale_percentage.toFixed(2)
            }
        },

        calculateUnitPrice(index){
            const currentAttribute = this.attributeDetails[index];
            const mrp = currentAttribute.mrp;
            const unit_percentage = currentAttribute.unit_percentage;
            if (mrp && unit_percentage){
                let cal_unit_price = (mrp - ((mrp * unit_percentage) / 100));
                currentAttribute.unit_price = cal_unit_price.toFixed(2)
            }
        },
        calculateSalePrice(index){
            const currentAttribute = this.attributeDetails[index];
            const mrp = currentAttribute.mrp;
            const sale_percentage = currentAttribute.sale_percentage;
            if (mrp && sale_percentage){
                let cal_sale_price = (mrp - ((mrp * sale_percentage) / 100));
                currentAttribute.sale_price = cal_sale_price.toFixed(2)
            }
        },

        async saveOpeningStock(){
            this.loading = true
            this.formData = {
                attributeDetails: this.attributeDetails,
                product_id: this.show.product.id,
                variant_order: this.show.product.variant_order,
            }
            await axios.post('/product/products-opening-stock', this.formData)
                .then(response => {
                    if (response.data.success) {
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound);
                        this.loading = false;
                        this.closeOpeningStock();
                    }else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound);
                        this.loading = false;
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.formState.validation = err.response.data.errors;
                        this.loading = false;
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err);
                        this.loading = false;
                    }
                })
        }
    }
}
</script>
<style scoped>
.tree {
    min-height:20px;
    margin-bottom:20px;
    background-color:#fbfbfb;
    -webkit-border-radius:4px;
    -moz-border-radius:4px;
    border-radius:4px;
    -webkit-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    -moz-box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05);
    box-shadow:inset 0 1px 1px rgba(0, 0, 0, 0.05)
}
.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, .tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:1px solid #999;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:1px solid #999;
    height:20px;
    top:25px;
    width:25px
}
.tree li span:not(.glyphicon) {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:5px;
    display:inline-block;
    padding:4px 9px;
    text-decoration:none
}
.tree li.parent_li>span:not(.glyphicon) {
    cursor:pointer
}
.tree>ul>li::before, .tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:30px
}
.tree li.parent_li>span:not(.glyphicon):hover, .tree li.parent_li>span:not(.glyphicon):hover+ul li span:not(.glyphicon) {
    background:#eee;
    border:1px solid #999;
    padding:3px 8px;
    color:#000
}
</style>
