<template>
    <div class="d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <i data-feather="corner-up-left" id="backToChatList"
               class="icon-lg mr-2 ml-n2 text-muted d-lg-none"></i>
            <figure class="mb-0 mr-2">
                <img src="/images/medicine.png" class="img-sm img-thumbnail rounded-circle" alt="image">
                <div class="status online" v-if="item.status === 1"></div>
                <div class="status offline" v-else></div>
            </figure>
            <div>
                <p class="font-weight-bolder text-capital">{{ value }}</p>
                <p class="text-muted tx-13"><b>{{ __('default.barcode') }}:</b> {{ item?.barcode }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProductName",
    props:['item', 'value']
}
</script>
<style scoped>
.text-capital{
    text-transform:capitalize;
}
figure {
    position: relative;
}

figure .status.online {
    background: #10b759;
}
figure .status.offline {
    background: #565555;
}

figure .status {
    width: 11px;
    height: 11px;
    background: #7987a1;
    position: absolute;
    bottom: 0px;
    right: -2px;
    border-radius: 50%;
    border: 2px solid #fff;
}
</style>
