<script>
export default {
    name: "AddNewRole",
    props: {
        formState: {
            type: Object,
            required: true
        }
    }
}
</script>

<template>
    <div>
        <a-drawer
            title="Add New Role"
            :width="720"
            :open="formState.openCreateRole"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onClose"
        >
            <a-form
                :model="formState.formData"
                v-bind="formState.layout"
                name="nest-messages"
                :validate-messages="formState.validateMessages"
                @finish=""
            >
                <a-form-item :name="['name']" label="Name" :rules="[{ required: true }]">
                    <a-input v-model:value="formState.formData.name"/>
                </a-form-item>
                <a-form-item :name="['description']" label="Description">
                    <a-textarea v-model:value="formState.formData.description"/>
                </a-form-item>
                <a-form-item :name="['permissions']" label="Permissions">
                    <div class="row">
                        <template v-for="(module, module_index) in formState.responsePermissions">
                            <div class="col-sm-6">
                                <div class="alert alert-secondary text-center cursor-pointer"
                                     data-toggle="collapse"
                                     :href="'#module_'+module_index"
                                     role="button"
                                     aria-expanded="false"
                                     :aria-controls="'module_'+module_index">
                                    {{ module.name }}
                                </div>
                                <div class="collapse show" :id="'module_'+module_index">
                                    <div class="form-group">
                                        <template v-for="(permission, permission_index) in module.permission">
                                            <div class="form-check">
                                                <label class="form-check-label cursor-pointer">
                                                    <input type="checkbox" class="form-check-input">
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
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onClose">
                    <i class="mdi mdi-window-close"></i> Close
                </a-button>
                <a-button type="primary" style="margin-right: 8px">
                    <i class="mdi mdi-content-save mr-1"></i> Save
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>

<style scoped>

</style>
