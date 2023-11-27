<template>
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-3 mb-md-0">
                                {{ __('default.purchase_report') }}
                            </h5>
                        </div>
                        <div>
                            <a-spin v-if="loader"/>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="row pt-2">
                        <div class="col-sm-3">
                            <a-form layout="horizontal">
                                <a-form-item label="Date">
                                    <a-range-picker :presets="rangePreset" style="width: 100%"/>
                                </a-form-item>
                            </a-form>
                        </div>
                        <div class="col-sm-3">
                            <a-form layout="horizontal">
                                <a-form-item label="Supplier">
                                    <a-select
                                        v-model:value="request.supplier"
                                        show-search
                                        placeholder="Select a supplier"
                                        style="width: 100%"
                                        :options="suppliers"
                                        :filter-option="selectFilterOption"
                                    ></a-select>
                                </a-form-item>
                            </a-form>
                        </div>
                        <div class="col-sm-3">
                            <a-form layout="horizontal">
                                <a-form-item label="Purchase status">
                                    <a-select
                                        v-model:value="request.purchase_status"
                                        multiple="multiple"
                                        show-search
                                        placeholder="Select purchase status"
                                        style="width: 100%"
                                        :options="suppliers"
                                        :filter-option="selectFilterOption"
                                    ></a-select>
                                </a-form-item>
                            </a-form>
                        </div>
                        <div class="col-sm-3">
                            <a-form layout="horizontal">
                                <a-form-item label="Pyment status">
                                    <a-select
                                        v-model:value="request.payment_status"
                                        multiple="multiple"
                                        show-search
                                        placeholder="Select payment status"
                                        style="width: 100%"
                                        :options="suppliers"
                                        :filter-option="selectFilterOption"
                                    ></a-select>
                                </a-form-item>
                            </a-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dayjs from 'dayjs';
export default {
    name: "Purchase",
    data() {
        return {
            loader: false,
            rangePreset:[
                { label: 'Last 7 Days', value: [dayjs().add(-7, 'd'), dayjs()] },
                { label: 'Last 14 Days', value: [dayjs().add(-14, 'd'), dayjs()] },
                { label: 'Last 30 Days', value: [dayjs().add(-30, 'd'), dayjs()] },
                { label: 'Last 90 Days', value: [dayjs().add(-90, 'd'), dayjs()] },
            ],
            suppliers:[],
            request:{
                date: null,
                supplier: null,
                purchase_status: null,
                payment_status: null,
            }
        }
    },
    methods:{
        selectFilterOption(input, option) {
            return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        },
    }
}
</script>
<style scoped>

</style>
