
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
                            <td>{{ __('default.sl') }}</td>
                            <td>{{ __('default.name') }}</td>
                            <td>{{ __('default.quantity') }}</td>
                            <td>{{ __('default.mrp') }}</td>
                            <td>{{ __('default.unit_price') }}</td>
                            <td>{{ __('default.sale_price') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="this.attributeDetails.length > 1">
                                <tr v-for="(attribute, attribute_index) in this.attributeDetails" :key="attribute_index">
                                    <td>{{ (attribute_index+1) }}</td>
                                    <td>{{ show.product.name.toUpperCase() }}-{{ attribute.Color }}-{{ attribute.Size }}</td>
                                    <td>
                                        <a-input-number id="quantity" v-model:value="attribute.quantity" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="mrp" v-model:value="attribute.mrp" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="unit_price" v-model:value="attribute.unit_price" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="sale_price" v-model:value="attribute.sale_price" style="width: 100%"/>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-for="(attribute, attribute_index) in this.attributeDetails" :key="attribute_index">
                                    <td>{{ (attribute_index+1) }}</td>
                                    <td>{{ show.product.name.toUpperCase() }}</td>
                                    <td>
                                        <a-input-number id="quantity" v-model:value="attribute.quantity" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="mrp" v-model:value="attribute.mrp" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="unit_price" v-model:value="attribute.unit_price" style="width: 100%"/>
                                    </td>
                                    <td>
                                        <a-input-number id="sale_price" v-model:value="attribute.sale_price" style="width: 100%"/>
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
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';
export default {
    name: "ProductDetails",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['show'],
    data(){
        return {
            attributeDetails: [],
            loading: false,
        }
    },
    watch: {
        show: {
            immediate: true,
            deep: true,
            handler() {
                if (this.show) {
                    this.combineAttributes();
                }
            },
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
        async saveOpeningStock(){
            this.loading = true
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
