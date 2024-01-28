
<template>
    <div class="text-center">

        <a-dropdown :trigger="['click']" :title="__('default.action')" :placement="'bottomRight'"
                    :arrow="{ pointAtCenter: true }" class="mr-1 dropdown-action">
            <MoreOutlined :style="{fontSize: '20px'}"/>
            <template #overlay>
                <a-menu>
                    <a-menu-item key="1" v-if="permission.payment_add && (row.payment_status === 'DUE' || row.payment_status === 'PARTIAL-PAID')" @click="$parent.$parent.showAddPaymentForm(row.id, row)">
                        <span class="color-warning">
                            <DollarOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                            {{ __('default.add_payment') }}
                        </span>
                    </a-menu-item>

                    <a-menu-item key="1" v-if="permission.show" @click="$parent.$parent.showDetails(row.id, row)">
                        <span class="color-info">
                            <EyeOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                            {{ __('default.show_details') }}
                        </span>
                    </a-menu-item>

                    <a-menu-item key="1" v-if="permission.edit && row.status !== 'received'" @click="goEditPage(row.id)">
                        <span class="color-primary">
                            <FormOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                            {{ __('default.edit') }}
                        </span>
                    </a-menu-item>

                    <a-menu-item key="1" v-if="permission.delete" @click="$parent.$parent.showDeleteForm(row.id)">
                        <span class="color-danger">
                            <DeleteOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                            {{ __('default.delete') }}
                        </span>
                    </a-menu-item>
                </a-menu>
            </template>
        </a-dropdown>
    </div>
</template>
<script>
import {
    MoreOutlined,
    FormOutlined,
    EyeOutlined,
    DeleteOutlined,
    DollarOutlined,
    CheckSquareOutlined
} from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{CheckSquareOutlined, DollarOutlined, DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['row', 'permission'],
    mounted() {

    },
    methods:{
        goEditPage(id){
            window.location.href = `purchases/${id}/edit`;
        }
    }
}
</script>
<style scoped>

</style>
