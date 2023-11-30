<template>
    <div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card radius-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-3 mb-md-0">
                                    {{ __('default.purchase') }}  {{ __('default.report') }}
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
