
<template>
    <div>
<!--        <a-dropdown :trigger="['click']">-->
<!--            <a class="ant-dropdown-link" @click.prevent style="cursor: pointer">-->
<!--                <more-outlined />-->
<!--            </a>-->
<!--            <template #overlay>-->
<!--                <a-menu>-->
<!--                    <a-menu-item key="0">-->
<!--                        <a href="http://www.alipay.com/">1st menu item</a>-->
<!--                    </a-menu-item>-->
<!--                    <a-menu-item key="1">-->
<!--                        <a href="http://www.taobao.com/">2nd menu item</a>-->
<!--                    </a-menu-item>-->
<!--                    <a-menu-divider />-->
<!--                    <a-menu-item key="3">3rd menu item</a-menu-item>-->
<!--                </a-menu>-->
<!--            </template>-->
<!--        </a-dropdown>-->

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
