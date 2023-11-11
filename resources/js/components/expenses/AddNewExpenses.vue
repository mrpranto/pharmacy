<template>
    <div>
        <a-drawer
            :title="__('default.add_expenses')"
            :width="720"
            :open="formState.openCreate"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            @close="$parent.onClose"
        >
            <a-form v-bind="formState.layout">

                <a-form-item :label="__('default.date')" required>
                    <a-input-group compact
                                   :class="formState.validation.date ? 'ant-input ant-input-status-error': ''">
                        <a-date-picker show-time v-model:value="formState.formData.date" style="width: 100%"/>
                    </a-input-group>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.date">{{
                            formState.validation.date[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.title')" required>
                    <a-input v-model:value="formState.formData.title" :placeholder="__('default.title')"
                             :class="formState.validation.title ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.title">{{
                            formState.validation.title[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.details')">
                    <a-textarea v-model:value="formState.formData.details" :placeholder="__('default.details')"
                                :class="formState.validation.details ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.details">
                        {{ formState.validation.details[0] }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.item_details')">
                    <a-space
                        v-for="(item, item_index) in formState.formData.item_details"
                        :key="item_index"
                        style="display: flex;"
                        align="baseline"
                    >
                        <a-form-item>
                            <a-input v-model:value="item.name" :placeholder="__('default.name')" />
                        </a-form-item>
                        <a-form-item>
                            <a-input type="number"
                                     v-model:value="item.amount"
                                     :placeholder="__('default.amount')"
                                     @input="calculateAmount"/>
                        </a-form-item>
                        <a-form-item>
                            <a-input v-model:value="item.note" :placeholder="__('default.note')" />
                        </a-form-item>
                        <MinusCircleOutlined @click="removeItems(item_index)" />
                    </a-space>

                    <a-form-item>
                        <a-button type="dashed" block @click="addNewItems">
                            <PlusOutlined />
                            {{ __('default.add_item') }}
                        </a-button>
                    </a-form-item>
                </a-form-item>

                <a-form-item :label="__('default.total')" required>
                    <a-input readonly="readonly" v-model:value="formState.formData.total_amount"
                             :class="formState.validation.total_amount ? 'ant-input ant-input-status-error': ''"/>
                    <div class="ant-form-item-explain-error" style="" v-if="formState.validation.total_amount">{{
                            formState.validation.total_amount[0]
                        }}
                    </div>
                </a-form-item>

                <a-form-item :label="__('default.attachment')" required>
                    <a-input-group compact>
                        <a-input readonly="readonly"
                                 v-model:value="attachment"
                                 style="width: calc(100% - 100px)"
                                 :placeholder="__('default.select_attachment')" />
                        <input type="file" hidden="hidden" id="fileUpload" @change="expensesAttachment">
                        <a-button @click.prevent="chooseFiles">
                            {{ __('default.select_file') }}
                        </a-button>
                    </a-input-group>
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
import {PaperClipOutlined} from '@ant-design/icons-vue';

export default {
    name: "AddNewExpenses",
    props: {
        formState: {
            type: Object,
            required: true
        }
    },
    components: {
        PaperClipOutlined
    },
    data() {
        return {
            attachment: '',
            formData: new FormData()
        }
    },
    watch: {

    },
    mounted() {

    },
    methods: {
        async save() {
            this.formData.append('title', this.formState.formData.title);
            this.formData.append('date', this.formState.formData.date);
            this.formData.append('details', this.formState.formData.details);
            this.formData.append('item_details', JSON.stringify(this.formState.formData.item_details));
            this.formData.append('total_amount', this.formState.formData.total_amount);
            await axios.post('/expanses', this.formData)
                .then(response => {
                    if (response.data.success) {
                        this.formState.formData.title = ''
                        this.formState.formData.date = ''
                        this.formState.formData.details = ''
                        this.formState.formData.item_details = []
                        this.formState.formData.total_amount = 0
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
        addNewItems(){
            this.formState.formData.item_details.push({
                name: '',
                amount: null,
                note: '',
            })
        },
        chooseFiles(){
            document.getElementById("fileUpload").click()
        },
        removeItems(key){
            this.formState.formData.item_details.splice(key, 1);
            this.calculateAmount();
        },
        calculateAmount(){
            let totalAmount = 0;
            this.formState.formData.item_details.forEach(item => {
                totalAmount += parseFloat(item.amount)
            })
            this.formState.formData.total_amount = totalAmount ? parseFloat(totalAmount).toFixed(2) : 0;
        },
        expensesAttachment(){
            this.formData.append('attachment', event.target.files[0])
            this.attachment = event.target.files[0]?.name
        }
    },
}
</script>

<style scoped>

</style>
