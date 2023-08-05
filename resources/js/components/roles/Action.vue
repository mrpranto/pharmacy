
<template>
    <div>
        <div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <more-outlined />
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton4">
                <a class="dropdown-item text-primary cursor-pointer" @click.prevent="$parent.$parent.getEditData(row)">
                    <i class="mdi mdi-lead-pencil text-primary"></i> {{ __('default.edit') }}
                </a>
                <a class="dropdown-item text-danger cursor-pointer" @click.prevent="$parent.$parent.showDeleteForm(row.id)">
                    <i class="mdi mdi-trash-can text-danger"></i> {{ __('default.delete') }}
                </a>
            </div>
        </div>
    </div>
</template>
<script>
import { MoreOutlined } from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{MoreOutlined},
    props:['row'],
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
