<template>
    <div>
        <a-tooltip :title="__('default.show_details')" class="mr-1">
            <EyeOutlined class="color-info" :style="{fontSize: '20px', marginLeft: '6px'}" @click="$parent.$parent.showDetails(row.id)"/>
        </a-tooltip>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit && row.is_delete_able === 1">
            <FormOutlined class="color-primary" :style="{fontSize: '20px', marginLeft: '6px'}"
                          @click.prevent="$parent.$parent.getEditData(row)"/>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')" v-if="permission.delete && row.is_delete_able === 1">
            <DeleteOutlined class="color-danger" :style="{fontSize: '20px', marginLeft: '6px'}"
                            @click.prevent="$parent.$parent.showDeleteForm(row.id)"/>
        </a-tooltip>
        <a-tooltip title="Can't change default permissions." v-if="row.is_delete_able === 0">
            <i class="mdi mdi-information cursor-pointer"></i>
        </a-tooltip>

    </div>
</template>
<script>
import {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined} from '@ant-design/icons-vue';

export default {
    name: "Action",
    components: {MoreOutlined, EyeOutlined, FormOutlined, DeleteOutlined},
    props: ['row', 'permission']
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
