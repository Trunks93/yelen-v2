<script setup lang="ts">

import Searchbar from "@/components/molecule/searchbar.vue";
import ServiceList from "@/components/molecule/serviceList.vue";

</script>
<template>
    <div class="agency-search-zone">
        <searchbar />
        <service-list />
    </div>
</template>

<style lang="scss" scoped>
.agency-search-zone{
    padding: 1.5rem;
    background-color: #ccc;
}
</style>
