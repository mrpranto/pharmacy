<template>
    <div class="text-center">

        <a-dropdown :trigger="['click']" :title="__('default.action')" :placement="'bottomRight'"
                    :arrow="{ pointAtCenter: true }" class="mr-1 dropdown-action">
            <MoreOutlined :style="{fontSize: '20px'}"/>
            <template #overlay>
                <a-menu>
                    <a-menu-item key="1" v-if="permission.payment_add" @click="$parent.$parent.showAddPaymentForm(row.id, row)">
                        <span class="color-warning">
                            <DollarOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                            {{ __('default.add_payment') }}
                        </span>
                    </a-menu-item>
                    <a-menu-item key="2" v-if="permission.change_status && row.status !== 'DELIVERED'" @click="$parent.$parent.showStatusForm(row.id, row)">
                        <span class="color-success">
                        <CheckSquareOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                        {{ __('default.change_status') }}
                        </span>
                    </a-menu-item>
                    <a-menu-item key="3"  v-if="permission.show" @click="$parent.$parent.showDetails(row.id)">
                        <span class="color-info">
                        <EyeOutlined :style="{fontSize: '15px', marginRight: '4px'}" />
                        {{ __('default.show_details') }}
                        </span>
                    </a-menu-item>
                    <a-menu-item key="4" v-if="permission.edit && row.status !== 'DELIVERED'" @click.prevent="goEditPage(row.id)">
                        <span class="color-primary">
                        <FormOutlined :style="{fontSize: '15px', marginRight: '4px'}"/>
                        {{ __('default.edit') }}
                        </span>
                    </a-menu-item>
                    <a-menu-item key="5" v-if="permission.delete && row.status !== 'DELIVERED'" @click.prevent="$parent.$parent.showDeleteForm(row.id)">
                        <span class="color-danger">
                        <DeleteOutlined :style="{fontSize: '15px', marginRight: '4px'}" />
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
    ShoppingCartOutlined,
    CheckSquareOutlined,
    DollarOutlined
} from '@ant-design/icons-vue';

export default {
    name: "Action",
    components: {
        DeleteOutlined,
        EyeOutlined,
        MoreOutlined,
        FormOutlined,
        ShoppingCartOutlined,
        CheckSquareOutlined,
        DollarOutlined
    },
    props: ['row', 'permission'],
    mounted() {

    },
    methods: {
        goEditPage(id) {
            window.location.href = `sales/${id}/edit`;
        }
    }
}
</script>
<style scoped>

</style>
