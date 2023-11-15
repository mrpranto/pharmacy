<template>
    <div>
        <span class="badge badge-pill badge-dark" @click.prevent="showItemDetails = true">
            {{ __('default.total_items') }} : {{ JSON.parse(value).length }}
        </span>
        <a-modal v-model:open="showItemDetails"
                 width="600px"
                 :ok-button-props="{ hidden: true }"
                 :cancel-button-props="{ hidden: true }"
                 :title="__('default.item_details')">

            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('default.sl') }}</th>
                                <th>{{ __('default.name') }}</th>
                                <th>{{ __('default.amount') }}</th>
                                <th>{{ __('default.note') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, item_details) in JSON.parse(value)" :key="item_details">
                                <td>
                                    {{ (item_details+1) }}
                                </td>
                                <td>{{ item.name }}</td>
                                <td>{{ $showCurrency(item.amount) }}</td>
                                <td>{{ item.note }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </a-modal>
    </div>
</template>

<script>
export default {
    name: "ItemDetails",
    props:['value'],
    data(){
        return{
            showItemDetails:false
        }
    }
}
</script>
<style scoped>

</style>
