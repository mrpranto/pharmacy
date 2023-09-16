<template>
    <div>
        <a-drawer
            :title="__('default.add_supplier')"
            :width="720"
            :open="formState.openCreate"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onClose"
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

                <a-form-item :label="__('default.phone_number')" required>
                    <a-input type="number" v-model:value="formState.formData.phone_number"
                             :class="formState.validation.phone_number ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <PhoneOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.phone_number">{{
                            formState.validation.phone_number[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.email')">
                    <a-input type="email" v-model:value="formState.formData.email"
                             :class="formState.validation.email ? 'ant-input ant-input-status-error': ''">
                        <template #prefix>
                            <UserOutlined style="color: rgba(0, 0, 0, 0.25)"/>
                        </template>
                    </a-input>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.email">{{
                            formState.validation.email[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.address')">
                    <a-textarea v-model:value="formState.formData.address"
                                :class="formState.validation.address ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.address">
                        {{ formState.validation.address[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.companies')">
                    <a-select
                        v-model:value="formState.formData.companies"
                        :class="formState.validation.companies ? 'ant-input ant-input-status-error': ''"
                        show-search
                        mode="multiple"
                        :placeholder="__('default.companies')"
                        :options="companies"
                        :filter-option="companyFilterOption"
                    ></a-select>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.companies">{{
                            formState.validation.companies[0]
                        }}
                    </div>
                </a-form-item>

            </a-form>
            <template #footer>
                <a-button type="primary" danger style="margin-right: 8px" @click="$parent.onClose">
                    <i class="mdi mdi-window-close"></i> {{ __('default.close') }}
                </a-button>
                <a-button type="primary" style="margin-right: 8px" @click.prevent="save">
                    <i class="mdi mdi-content-save mr-1"></i> {{ __('default.save') }}
                </a-button>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import {notification} from 'ant-design-vue';
import {UserOutlined, LockOutlined, PhoneOutlined, UploadOutlined} from '@ant-design/icons-vue';

export default {
    name: "AddNewSupplier",
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    components: {UserOutlined, LockOutlined, PhoneOutlined, UploadOutlined},
    data() {
        return {
            companies:[]
        }
    },
    watch: {
        'formState.responseCompanies':{
            immediate: true,
            deep: true,
            handler() {
                if (this.formState.responseCompanies) {
                    this.getCompanies();
                }
            },
        }
    },
    mounted() {

    },
    methods: {
        async save() {
            await axios.post('/peoples/suppliers', this.formState.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.name = ''
                        this.formState.formData.phone_number = ''
                        this.formState.formData.email = ''
                        this.formState.formData.address = ''
                        this.formState.formData.companies = []
                        this.$parent.getData()
                        this.$parent.onClose()
                        this.$showSuccessMessage(response.data.success, this.$notification_position, this.$notification_sound)
                    } else {
                        this.$showErrorMessage(response.data.error, this.$notification_position, this.$notification_sound)
                    }
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        this.$showErrorMessage(err.response.data.message, this.$notification_position, this.$notification_sound)
                        this.formState.validation = err.response.data.errors
                    } else {
                        this.$showErrorMessage(err, this.$notification_position, this.$notification_sound)
                        console.error(err)
                    }
                })
        },
        getCompanies(){
            this.companies = this.formState.responseCompanies.map(item => {
                return {
                    value:item.id,
                    label:item.name
                }
            })
        },
        companyFilterOption(input, option){
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
    },
}
</script>

<style scoped>

</style>
