
<template>
    <div>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit && row.is_delete_able === 1">
            <a-button shape="circle" @click.prevent="$parent.$parent.getEditData(row)"><i class="mdi mdi-lead-pencil"></i></a-button>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')"  v-if="permission.delete && row.is_delete_able === 1">
            <a-button danger shape="circle" @click.prevent="$parent.$parent.showDeleteForm(row.id)"><i class="mdi mdi-trash-can"></i></a-button>
        </a-tooltip>
        <a-tooltip title="Can't change default permissions."  v-if="row.is_delete_able === 0">
            <i class="mdi mdi-information cursor-pointer"></i>
        </a-tooltip>
    </div>
</template>
<script>
import { MoreOutlined } from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{MoreOutlined},
    props:['row', 'permission'],
    mounted() {

    }
}
</script>
<style scoped>
.dropdown-toggle {
    cursor: pointer;
}
.dropdown-toggle::after {
    content: none;
}
</style>
