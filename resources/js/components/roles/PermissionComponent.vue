<template>
    <div>
        <span class="badge badge-pill badge-primary m-1"
              v-for="(permission, permission_index) in value" :key="permission_index">{{ permission.name }}</span>
    </div>
</template>
<script>
export default {
    name: "PermissionComponent",
    props: ['value'],
    methods: {}
}
</script>
<style scoped>

</style>
