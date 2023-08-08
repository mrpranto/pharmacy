<template>
    <div>
        <a-drawer
            :title="__('default.edit_category')"
            :width="720"
            :open="formState.openEdit"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onEditClose"
        >
            <a-form v-bind="formState.layout">
                <a-form-item :label="__('default.name')" required>
                    <a-input v-model:value="formState.formData.name" required=""
                             :class="formState.validation.name ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.name">{{
                            formState.validation.name[0]
                        }}
                    </div>
                </a-form-item>
                <a-form-item :name="['description']" :label="__('default.description')">
                    <a-textarea v-model:value="formState.formData.description" :rows="6"
                                :class="formState.validation.description ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.description">
                        {{ formState.validation.description[0] }}
                    </div>
                </a-form-item>
                <a-form-item :label="__('default.status')">
                    <a-switch v-model:checked="formState.formData.status" />
                </a-form-item>

            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onEditClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button type="primary" style="margin-right: 8px" @click.prevent="updateCategory">
                    <i class="mdi mdi-check-all mr-1"></i> {{ __('default.update') }}
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import {notification} from 'ant-design-vue';

export default {
    name: "EditCategory",
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    data() {
        return {

        }
    },
    watch: {

    },
    methods: {
        async updateCategory() {
            await axios.put(`/product/categories/${this.formState.current_id}`, this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.description = ''
                        this.formState.formData.status = true
                        this.$parent.getData()
                        this.$parent.onEditClose()
                        this.showSuccessMessage(response.data.success)
                    } else {
                        this.showErrorMessage(response.data.error)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.formState.validation = err.response.data.errors
                    } else {
                        console.error(err)
                    }
                })
        },
        showSuccessMessage(message) {
            notification['success']({
                message: 'Success',
                description: message,
            });
        },
        showErrorMessage(message) {
            notification['error']({
                message: 'Error',
                description: message,
            });
        },
    },
}
</script>

<style scoped>

</style>
