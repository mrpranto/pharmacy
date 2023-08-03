
<template>
    <div>
        <div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <more-outlined />
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton4">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Separated link</a>
            </div>
        </div>
    </div>
</template>
<script>
import { MoreOutlined } from '@ant-design/icons-vue';
export default {
    name: "Action",
    components:{MoreOutlined}
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
