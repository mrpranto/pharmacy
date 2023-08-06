<template>
    <span>
        | <small>{{ __('default.total') }} : {{ total }}</small>
    </span>
</template>
<script>
export default {
    name: "Counter",
    props: ['total']
}
</script>

<style scoped>

</style>
