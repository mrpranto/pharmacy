<template>
    <div>
        <a-modal v-model:open="show.open"
                 width="40%"
                 style="top: 10px"
                 :title="__('default.role_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9"> {{ show.role.name }}</dd>

                <dt class="col-sm-3">{{ __('default.description') }}</dt>
                <dd class="col-sm-9"> {{ show.role.description }}</dd>

                <dt class="col-sm-3">{{ __('default.permissions') }}</dt>
                <dd class="col-sm-9">
                    <template v-for="(permission) in show.role.permissions">
                        <span class="badge badge-pill badge-primary m-1"> <small>{{ permission.module.name }}</small> <i
                            class="mdi mdi-arrow-right"></i> {{ permission.name }}</span>
                    </template>
                </dd>

                <dt class="col-sm-3">{{ __('default.users') }}</dt>
                <dd class="col-sm-9">

                    <a-avatar-group :max-count="12" :max-style="{ color: '#f56a00', backgroundColor: '#fde3cf' }">
                        <template v-for="(user) in show.role.users">
                        <a-tooltip v-if="user.profile_picture" :title="user.name" placement="top">
                            <a-avatar :src="user.profile_picture?.full_url"/>
                        </a-tooltip>
                        <a-tooltip v-else :title="user.name" placement="top">
                            <a-avatar>{{ user.name.charAt(0) }}</a-avatar>
                        </a-tooltip>
                        </template>
                    </a-avatar-group>

                </dd>

                <dt class="col-sm-3" v-if="show.role.created_by">{{ __('default.created_by') }}</dt>
                <dd class="col-sm-9" v-if="show.role.created_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.role.created_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.role.created_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.role.created_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.role.created_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.role.created_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.role.created_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>


                <dt class="col-sm-3" v-if="show.role.updated_by">{{ __('default.updated_by') }}</dt>
                <dd class="col-sm-9" v-if="show.role.updated_by">
                    <div class="d-flex align-items-center">
                        <i data-feather="corner-up-left" id="backToChatList"
                           class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
                        <figure class="mb-0 mr-2">
                            <template v-if="show.role.updated_by?.profile_picture">
                                <a-image
                                    :width="35"
                                    :height="35"
                                    :src="show.role.updated_by?.profile_picture?.full_url"
                                    class="img-sm rounded-circle"
                                    :alt="show.role.updated_by?.name"
                                />
                            </template>
                            <template v-else>
                                <a-image
                                    :width="35"
                                    :height="35"
                                    src="/images/avatar.png"
                                    class="img-sm rounded-circle"
                                    :alt="show.role.updated_by?.name"
                                />
                            </template>
                            <div class="status online"></div>
                        </figure>
                        <div>
                            <p class="font-weight-bolder text-capital">{{ show.role.updated_by?.name }}</p>
                            <p class="text-muted tx-13"><b>{{ __('default.role') }}:</b> {{
                                    show.role.updated_by?.role?.name
                                }}</p>
                        </div>
                    </div>
                </dd>

                <dt class="col-sm-3">{{ __('default.created_at') }}</dt>
                <dd class="col-sm-9"> {{ show.role.created_at }}</dd>

                <dt class="col-sm-3">{{ __('default.updated_at') }}</dt>
                <dd class="col-sm-9"> {{ show.role.updated_at }}</dd>

            </dl>

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined} from '@ant-design/icons-vue';

export default {
    name: "RoleDetails",
    components: {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined},
    props: ['show'],
}
</script>
<style scoped>

</style>
