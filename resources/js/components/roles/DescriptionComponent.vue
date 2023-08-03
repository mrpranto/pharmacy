<template>
    <div>
        <span>{{ showLimitedText() }}</span>
    </div>
</template>
<script>
export default {
    name: "DescriptionComponent",
    props: ['value'],
    methods:{
        showLimitedText(){
            if (this.value.length > 80) {
                return this.value.substring(0, 80) + ' ...';
            }else {
                return this.value
            }
        }
    }
}
</script>
<style scoped>

</style>
