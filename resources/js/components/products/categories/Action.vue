
<template>
    <div>
        <a-tooltip :title="__('default.show_details')" class="mr-1">
            <EyeOutlined class="color-info" :style="{fontSize: '20px', marginLeft: '6px'}" @click="showDetails"/>
        </a-tooltip>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit">
            <FormOutlined class="color-primary" :style="{fontSize: '20px', marginLeft: '6px'}"
                          @click.prevent="$parent.$parent.getEditData(row)"/>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')"  v-if="permission.delete">
            <DeleteOutlined class="color-danger" :style="{fontSize: '20px', marginLeft: '6px'}"
                            @click.prevent="$parent.$parent.showDeleteForm(row.id)"/>
        </a-tooltip>


        <a-modal v-model:open="open"
                 width="40%"
                 style="top: 10px"
                 :title="__('default.role_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

<!--            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9"> {{ role.name }}</dd>

                <dt class="col-sm-3">{{ __('default.description') }}</dt>
                <dd class="col-sm-9"> {{ role.description }}</dd>

                <dt class="col-sm-3" v-if="role.created_by">{{ __('default.created_by') }}</dt>
                <dd class="col-sm-9" v-if="role.created_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="role.created_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="role.created_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="role.created_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="role.created_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ role.created_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    role.created_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>


                <dt class="col-sm-3" v-if="role.updated_by">{{ __('default.updated_by') }}</dt>
                <dd class="col-sm-9" v-if="role.updated_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="role.updated_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="role.updated_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="role.updated_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="role.updated_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ role.updated_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    role.updated_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.created_at') }}</dt>
                <dd class="col-sm-9"> {{ role.created_at }}</dd>

                <dt class="col-sm-3">{{ __('default.updated_at') }}</dt>
                <dd class="col-sm-9"> {{ role.updated_at }}</dd>

            </dl>-->

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['row', 'permission'],
    data() {
        return {
            open: false,
            category: {}
        }
    },
    mounted() {

    },
    methods:{
        async showDetails() {
            await axios.get('/product/categories/' + this.row.id)
                .then(response => {
                    this.category = response.data
                    this.open = true
                })
                .catch(err => {
                    console.error(err)
                })
        }
    }
}
</script>
<style scoped>

</style>
