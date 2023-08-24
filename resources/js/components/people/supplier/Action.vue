
<template>
    <div>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit">
            <a-button shape="circle" @click.prevent="$parent.$parent.getEditData(row)"><i class="mdi mdi-lead-pencil"></i></a-button>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')"  v-if="permission.delete">
            <a-button shape="circle" @click.prevent="$parent.$parent.showDeleteForm(row.id)"><i class="mdi mdi-trash-can"></i></a-button>
        </a-tooltip>
    </div>
</template>
<script>
import { MoreOutlined, FormOutlined } from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{MoreOutlined, FormOutlined},
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
