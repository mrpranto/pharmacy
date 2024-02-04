<template>
    <div>
        <a-drawer
            :title="__('default.edit_attribute')"
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

                <a-form-item :label="__('default.details')">
                    <a-space
                        v-for="(item, item_index) in formState.formData.details"
                        :key="item_index"
                        style="display: flex;"
                        align="baseline"
                    >
                        <a-form-item>
                            <a-input v-model:value="item.name" :placeholder="__('default.item_name')" />
                        </a-form-item>
                        <a-form-item>
                            <a-input v-model:value="item.note" :placeholder="__('default.note')" />
                        </a-form-item>
                        <MinusCircleOutlined v-if="(item_index + 1) !==  formState.formData.details.length" @click="removeItems(item_index)" :style="{fontSize: '20px'}" class="color-danger"/>
                        <PlusCircleOutlined v-if="(item_index + 1) ===  formState.formData.details.length" @click="addNewItems" :style="{fontSize: '20px'}" class="color-primary"/>
                    </a-space>

                </a-form-item>

                <a-form-item :label="__('default.status')">
                    <a-switch v-model:checked="formState.formData.status" />
                </a-form-item>

            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button v-if="loading" type="primary" style="margin-right: 8px" loading>
                    {{ __('default.loading') }}
                </a-button>
                <a-button v-else type="primary" style="margin-right: 8px" @click.prevent="updateAttribute">
                    <i class="mdi mdi-check-all mr-1"></i> {{ __('default.update') }}
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import {notification} from 'ant-design-vue';
import {MinusCircleOutlined, PlusCircleOutlined, PlusOutlined} from "@ant-design/icons-vue";

export default {
    name: "EditAttribute",
    components: {PlusOutlined, MinusCircleOutlined, PlusCircleOutlined},
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            loading:false
        }
    },
    watch: {

    },
    methods: {
        addNewItems(){
            this.formState.formData.details.push({
                name: '',
                note: '',
            })
        },
        removeItems(key){
            this.formState.formData.details.splice(key, 1);
        },
        async updateAttribute() {
            this.loading = true
            await axios.put(`/product/attributes/${this.formState.current_id}`, this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.details = []
                        this.formState.formData.status = true
                        let current_path = this.formState.list_path+'?page=' + this.formState.current_list_url
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
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                        this.loading = false
                    }
                })
        }
    },
}
</script>

<style scoped>

</style>
