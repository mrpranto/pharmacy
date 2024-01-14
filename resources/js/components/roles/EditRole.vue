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
                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="formState.formData.name"
                             :class="formState.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.name">{{
                            formState.validation.name[0]
                        }}
                    </div>
                </a-form-item>
                <a-form-item :label="__('default.description')" required>
                    <a-textarea v-model:value="formState.formData.description"
                                :class="formState.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.description">
                        {{ formState.validation.description[0] }}
                    </div>
                </a-form-item>
                <a-form-item :label="__('default.is_delete_able')">
                    <a-switch v-model:checked="formState.formData.isDeleteAble"/>
                </a-form-item>
                <a-form-item :name="['permissions']" :label="__('default.permissions')" required>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ant-form-item-explain-error mt-2" style="" v-if="formState.validation.permissions">
                                {{ formState.validation.permissions[0] }}
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
                                                       v-model="formState.formData.module_ids"
                                                       @change="selectEditGroupPermission(module.id)">
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
                <a-button v-if="loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else type="primary" style="margin-right: 8px" @click.prevent="editRole">
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
            loading: false
        }
    },
    watch: {
        'formState.selectAll': function () {
            this.selectAllPermission()
        }
    },
    methods: {
        async editRole() {
            this.loading = true
            await axios.put(`/roles/${this.formState.current_id}`, this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.description = ''
                        this.formState.formData.permissions = []
                        let current_path = this.formState.list_path + '?page=' + this.formState.current_list_url
                        this.$parent.getData(current_path)
                        this.$parent.onEditClose()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                        this.loading = false
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                        this.loading = false
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.formState.validation = err.response.data.errors
                        this.loading = false
                    } else {
                        console.error(err)
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        this.loading = false
                    }
                })
        },
        selectAllPermission() {
            const permissions = [];
            const module_ids = [];
            if (this.formState.selectAll === true) {
                this.formState.responsePermissions.forEach(item => {
                    item.permission.forEach(subItem => {
                        permissions.push(subItem.id)
                    })
                    module_ids.push(item.id)
                })
                this.formState.formData.permissions = permissions
                this.formState.formData.module_ids = module_ids
            }else {
                this.formState.formData.module_ids = []
                this.formState.formData.permissions = []
            }
        },
        selectEditGroupPermission(moduleId) {
            this.formState.responsePermissions.forEach(item => {
                if (event.target.checked === true && item.id === moduleId) {
                    item.permission.forEach(subItem => {
                        this.formState.formData.permissions.push(subItem.id)
                    })
                }else if (event.target.checked === false && item.id === moduleId) {
                    item.permission.forEach(subItem => {
                        this.formState.formData.permissions = this.formState.formData.permissions.filter(item => item !== subItem.id)
                    })
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
