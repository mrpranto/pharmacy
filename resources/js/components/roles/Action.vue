<template>
    <div>
        <a-tooltip :title="__('default.show_details')" class="mr-1">
            <EyeOutlined :style="{fontSize: '20px', marginLeft: '6px'}" @click="showDetails"/>
        </a-tooltip>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit && row.is_delete_able === 1">
            <FormOutlined :style="{fontSize: '20px', marginLeft: '6px'}"
                          @click.prevent="$parent.$parent.getEditData(row)"/>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')" v-if="permission.delete && row.is_delete_able === 1">
            <DeleteOutlined :style="{fontSize: '20px', marginLeft: '6px'}"
                            @click.prevent="$parent.$parent.showDeleteForm(row.id)"/>
        </a-tooltip>
        <a-tooltip title="Can't change default permissions." v-if="row.is_delete_able === 0">
            <i class="mdi mdi-information cursor-pointer"></i>
        </a-tooltip>

        <a-modal v-model:open="open"
                 width="40%"
                 :title="__('default.role_details')"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: false }">

            <hr>

            <dl class="row">
                <dt class="col-sm-3">{{ __('default.name') }}</dt>
                <dd class="col-sm-9"> {{ role.name }}</dd>

                <dt class="col-sm-3">{{ __('default.description') }}</dt>
                <dd class="col-sm-9"> {{ role.description }}</dd>

                <dt class="col-sm-3">{{ __('default.permissions') }}</dt>
                <dd class="col-sm-9">
                    <template v-for="(permission) in role.permissions">
                        <span class="badge badge-pill badge-primary m-1"> <small>{{ permission.module.name }}</small> <i
                            class="mdi mdi-arrow-right"></i> {{ permission.name }}</span>
                    </template>
                </dd>

                <dt class="col-sm-3">{{ __('default.users') }}</dt>
                <dd class="col-sm-9">

                    <a-avatar-group :max-count="20" :max-style="{ color: '#f56a00', backgroundColor: '#fde3cf' }">
                        <template v-for="(user) in role.users">
                        <a-tooltip v-if="user.profile_picture" :title="user.name" placement="top">
                            <a-avatar :src="user.profile_picture?.full_url"/>
                        </a-tooltip>
                        <a-tooltip v-else :title="user.name" placement="top">
                            <a-avatar>{{ user.name.charAt(0) }}</a-avatar>
                        </a-tooltip>
                        </template>
                    </a-avatar-group>

                </dd>

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

            </dl>

            <hr>
        </a-modal>
    </div>
</template>
<script>
import {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined} from '@ant-design/icons-vue';

export default {
    name: "Action",
    components: {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined},
    props: ['row', 'permission'],
    data() {
        return {
            open: false,
            role: {}
        }
    },
    mounted() {

    },
    methods: {
        async showDetails() {
            await axios.get('/roles/' + this.row.id)
                .then(response => {
                    this.role = response.data
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
.dropdown-toggle {
    cursor: pointer;
}

.dropdown-toggle::after {
    content: none;
}
</style>
