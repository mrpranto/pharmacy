
<template>
    <div>
        <a-modal v-model:open="show.open"
                 width="40%"
                 style="top: 10px"
                 :title="__('default.user_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9">: {{ show.user.name }}</dd>

                <dt class="col-sm-3">{{ __('default.phone_number') }}</dt>
                <dd class="col-sm-9">: {{ show.user.phone_number }}</dd>

                <dt class="col-sm-3">{{ __('default.email') }}</dt>
                <dd class="col-sm-9">: {{ show.user.email }}</dd>

                <dt class="col-sm-3">{{ __('default.role') }}</dt>
                <dd class="col-sm-9">: {{ show.user.role?.name }}</dd>

                <dt class="col-sm-3" v-if="show.user.created_by">{{ __('default.created_by') }}</dt>
                <dd class="col-sm-9" v-if="show.user.created_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.user.created_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.user.created_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.created_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.created_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.user.created_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.user.created_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>


                <dt class="col-sm-3" v-if="show.user.updated_by">{{ __('default.updated_by') }}</dt>
                <dd class="col-sm-9" v-if="show.user.updated_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.user.updated_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.user.updated_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.updated_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.updated_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.user.updated_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.user.updated_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.created_at') }}</dt>
                <dd class="col-sm-9">: {{ show.user.created_at }}</dd>

                <dt class="col-sm-3">{{ __('default.updated_at') }}</dt>
                <dd class="col-sm-9">: {{ show.user.updated_at }}</dd>

                <dt class="col-sm-3">{{ __('default.profile_picture') }}</dt>
                <dd class="col-sm-9">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.user.profile_picture">
                                <a-image
                                    :width="65"
                                    :height="65"
                                    :src="show.user.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="65"
                                    :height="65"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.user.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                    </div>
                </dd>


                <dt class="col-sm-3">{{ __('default.gender') }}</dt>
                <dd class="col-sm-9">: {{ show.user.gender ?? __('default.not_added_yet') }}</dd>

                <dt class="col-sm-3">{{ __('default.address') }}</dt>
                <dd class="col-sm-9">: {{ show.user.address ?? __('default.not_added_yet')  }}</dd>

            </dl>

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {DeleteOutlined, EyeOutlined, FormOutlined, MoreOutlined} from '@ant-design/icons-vue';
export default {
    name: "UserDetails",
    components:{DeleteOutlined, FormOutlined, EyeOutlined, MoreOutlined},
    props:['show'],
}
</script>
<style scoped>

</style>
