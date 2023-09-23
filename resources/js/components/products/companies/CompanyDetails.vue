
<template>
    <div>

        <a-modal v-model:open="show.open"
                 width="40%"
                 style="top: 10px"
                 :title="__('default.company_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9"> {{ show.company.name }}</dd>

                <dt class="col-sm-3">{{ __('default.email') }}</dt>
                <dd class="col-sm-9"> {{ show.company.email }}</dd>

                <dt class="col-sm-3">{{ __('default.phone_number') }}</dt>
                <dd class="col-sm-9"> {{ show.company.phone_number }}</dd>

                <dt class="col-sm-3">{{ __('default.description') }}</dt>
                <dd class="col-sm-9"> {{ show.company.description }}</dd>

                <dt class="col-sm-3">{{ __('default.status') }}</dt>
                <dd class="col-sm-9">
                    <span v-if="show.company.status == '1'" class="badge badge-primary">Active</span>
                    <span v-else class="badge badge-danger">In-active </span>
                </dd>

                <dt class="col-sm-3" v-if="show.company.created_by">{{ __('default.created_by') }}</dt>
                <dd class="col-sm-9" v-if="show.company.created_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.company.created_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.company.created_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.company.created_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.company.created_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.company.created_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.company.created_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>


                <dt class="col-sm-3" v-if="show.company.updated_by">{{ __('default.updated_by') }}</dt>
                <dd class="col-sm-9" v-if="show.company.updated_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.company.updated_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.company.updated_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.company.updated_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.company.updated_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.company.updated_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.company.updated_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.created_at') }}</dt>
                <dd class="col-sm-9"> {{ show.company.created_at }}</dd>

                <dt class="col-sm-3">{{ __('default.updated_at') }}</dt>
                <dd class="col-sm-9"> {{ show.company.updated_at }}</dd>

            </dl>

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';
export default {
    name: "CompanyDetails",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['show'],
}
</script>
<style scoped>

</style>
