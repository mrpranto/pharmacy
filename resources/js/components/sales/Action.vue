
<template>
    <div>
        <a-tooltip :title="__('default.change_status')" class="mr-1" v-if="permission.change_status && row.status !== 'DELIVERED'">
            <CheckSquareOutlined class="color-success" :style="{fontSize: '20px', marginLeft: '6px'}" @click="$parent.$parent.showStatusForm(row.id, row)"/>
        </a-tooltip>

        <a-tooltip :title="__('default.show_details')" class="mr-1"  v-if="permission.show">
            <EyeOutlined class="color-info" :style="{fontSize: '20px', marginLeft: '6px'}"
                         @click="$parent.$parent.showDetails(row.id)"/>
        </a-tooltip>

        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit && row.status !== 'DELIVERED'">
                <FormOutlined class="color-primary" :style="{fontSize: '20px', marginLeft: '6px'}"
                              @click.prevent="goEditPage(row.id)"/>
        </a-tooltip>

        <a-tooltip :title="__('default.delete')"  v-if="permission.delete && row.status !== 'DELIVERED'">
            <DeleteOutlined class="color-danger" :style="{fontSize: '20px', marginLeft: '6px'}"
                            @click.prevent="$parent.$parent.showDeleteForm(row.id)"/>
        </a-tooltip>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined, ShoppingCartOutlined, CheckSquareOutlined} from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined, ShoppingCartOutlined, CheckSquareOutlined},
    props:['row', 'permission'],
    mounted() {

    },
    methods:{
        goEditPage(id){
            window.location.href = `sales/${id}/edit`;
        }
    }
}
</script>
<style scoped>

</style>
