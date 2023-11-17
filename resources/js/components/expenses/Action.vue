
<template>
    <div>
        <a-tooltip :title="__('default.edit')" class="mr-1" v-if="permission.edit">
            <FormOutlined class="color-primary" :style="{fontSize: '20px', marginLeft: '6px'}"
                          @click.prevent="$parent.$parent.getEditData(row)"/>
        </a-tooltip>
        <a-tooltip :title="__('default.delete')"  v-if="permission.delete">
            <DeleteOutlined class="color-danger" :style="{fontSize: '20px', marginLeft: '6px'}"
                            @click.prevent="$parent.$parent.showDeleteForm(row.id)"/>
        </a-tooltip>
    </div>
</template>
<script>
import {MoreOutlined, FormOutlined, EyeOutlined, DeleteOutlined} from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{DeleteOutlined, EyeOutlined, MoreOutlined, FormOutlined},
    props:['row', 'permission'],
    mounted() {

    }
}
</script>
<style scoped>

</style>
