
<template>
    <div>

        <a-modal v-model:open="show.open"
                 width="40%"
                 style="top: 10px"
                 :title="__('default.product_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9"> {{ show.product.name }}</dd>

                <dt class="col-sm-3">{{ __('default.barcode') }}</dt>
                <dd class="col-sm-9"> {{ show.product.barcode }}</dd>

                <dt class="col-sm-3">{{ __('default.category') }}</dt>
                <dd class="col-sm-9"> {{ show.product.category.name }}</dd>

                <dt class="col-sm-3">{{ __('default.company') }}</dt>
                <dd class="col-sm-9"> {{ show.product.company.name }}</dd>

                <dt class="col-sm-3">{{ __('default.unit') }}</dt>
                <dd class="col-sm-9"> {{ show.product.unit.name }} ({{ show.product.unit.pack_size }})</dd>

                <dt class="col-sm-3">{{ __('default.description') }}</dt>
                <dd class="col-sm-9"> {{ show.product.description }}</dd>

                <dt class="col-sm-3">{{ __('default.purchase_type') }}</dt>
                <dd class="col-sm-9">
                    <template v-if="show.product.purchase_type == '%'">
                        <span class="badge badge-info">%</span> Percentage
                    </template>
                    <template v-else>
                        <span class="badge badge-success">{{ $currency_symbol }}</span> Direct Price
                    </template>
                </dd>

                <dt class="col-sm-3">{{ __('default.status') }}</dt>
                <dd class="col-sm-9">
                    <span v-if="show.product.status == '1'" class="badge badge-primary">Active</span>
                    <span v-else class="badge badge-danger">In-active </span>
                </dd>

                <dt class="col-sm-3">{{ __('default.attributes') }}</dt>
                <dd class="col-sm-9">

                    <div id="test" class="tree" v-if="Object.keys(show.product.attributes).length">
                        <ul>
                            <li class="parent_li" v-for="(attribute, attribute_index) in show.product.attributes" :key="attribute_index">
                                <span title="Verkleinern">{{ attribute_index }}</span>
                                <ul>
                                    <li class="parent_li" v-for="(item, item_index) in attribute" :key="item_index">
                                        <span title="Verkleinern">{{ item.value }}</span>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.image') }}</dt>
                <dd class="col-sm-9">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
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
                </dd>

                <dt class="col-sm-3" v-if="show.product.created_by">{{ __('default.created_by') }}</dt>
                <dd class="col-sm-9" v-if="show.product.created_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.product.created_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.product.created_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.product.created_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.product.created_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.product.created_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.product.created_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>


                <dt class="col-sm-3" v-if="show.product.updated_by">{{ __('default.updated_by') }}</dt>
                <dd class="col-sm-9" v-if="show.product.updated_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.product.updated_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.product.updated_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.product.updated_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.product.updated_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.product.updated_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.product.updated_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.created_at') }}</dt>
                <dd class="col-sm-9"> {{ show.product.created_at }}</dd>

                <dt class="col-sm-3">{{ __('default.updated_at') }}</dt>
                <dd class="col-sm-9"> {{ show.product.updated_at }}</dd>

            </dl>

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';
export default {
    name: "ProductDetails",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['show'],
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
