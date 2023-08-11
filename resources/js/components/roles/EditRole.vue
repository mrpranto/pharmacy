<template>
    <div>
        <a-drawer
            :title="__('default.edit_role')"
            :width="720"
            :open="formState.openEditRole"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onEditClose"
        >
            <a-form v-bind="formState.layout">
                <a-form-item :label="__('default.name')">
                    <a-input v-model:value="formState.formData.name"
                             :class="validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.name">{{
                            validation.name[0]
                        }}
                    </div>
                </a-form-item>
                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="formState.formData.description"
                                :class="validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="validation.description">
                        {{ validation.description[0] }}
                    </div>
                </a-form-item>
                <a-form-item :label="__('default.is_delete_able')">
                    <a-switch v-model:checked="formState.formData.isDeleteAble" />
                </a-form-item>
                <a-form-item :name="['permissions']" :label="__('default.permissions')">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ant-form-item-explain-error mt-2" style="" v-if="validation.permissions">
                                {{ validation.permissions[0] }}
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label cursor-pointer">
                                        <input type="checkbox" class="form-check-input" v-model="formState.selectAll">
                                        {{ __('default.select_all') }}
                                        <i class="input-frame"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <template v-for="(module, module_index) in formState.responsePermissions">
                            <div class="col-sm-6">
                                <div class="alert alert-secondary text-center cursor-pointer"
                                     :href="'#module_'+module_index"
                                     role="button"
                                     aria-expanded="false"
                                     :aria-controls="'module_'+module_index">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label cursor-pointer">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       :value="module.id"
                                                       v-model="formState.formData.module_ids">
                                                {{ module.name }}
                                                <i class="input-frame"></i></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="show" :id="'module_'+module_index">
                                    <div class="form-group">
                                        <template v-for="(permission) in module.permission">
                                            <div class="form-check">
                                                <label class="form-check-label cursor-pointer">
                                                    <input type="checkbox"
                                                           class="form-check-input"
                                                           :value="permission.id"
                                                           v-model="formState.formData.permissions">
                                                    {{ permission.name }}
                                                    <i class="input-frame"></i></label>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </a-form-item>

            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onEditClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button type="primary" style="margin-right: 8px" @click.prevent="editRole">
                    <i class="mdi mdi-check-all mr-1"></i> {{ __('default.update') }}
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import {notification} from 'ant-design-vue';

export default {
    name: "EditRole",
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            validation: {},
        }
    },
    watch: {
        'formState.selectAll': function () {
            this.selectAllPermission()
        },
        'formState.formData.module_ids': function () {
            this.selectEditGroupPermission()
        }
    },
    methods: {
        async editRole() {
            await axios.put(`/roles/${this.formState.current_id}`, this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.description = ''
                        this.formState.formData.permissions = []
                        this.$parent.getData()
                        this.$parent.onEditClose()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.validation = err.response.data.errors
                    } else {
                        console.error(err)
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                    }
                })
        },
        selectAllPermission() {
            const permissions = [];
            if (this.formState.selectAll) {
                this.formState.responsePermissions.forEach(item => {
                    item.permission.forEach(subItem => {
                        permissions.push(subItem.id)
                    })
                })
                this.formState.formData.permissions = permissions
            }else {
                this.formState.formData.permissions = []
            }
        },
        selectEditGroupPermission() {
            const permissions = [];
            if (this.formState.formData.module_ids.length > 0){
                this.formState.responsePermissions.forEach(item => {
                    if (this.formState.formData.module_ids.includes(item.id)) {
                        item.permission.forEach(subItem => {
                            permissions.push(subItem.id)
                        })
                    }
                })
                this.formState.formData.permissions = permissions
            }
        }
    },
}
</script>

<style scoped>

</style>
