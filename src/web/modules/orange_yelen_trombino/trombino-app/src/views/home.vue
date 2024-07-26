<script setup lang="ts">
import AgencyMap from "@/components/organism/agency-map.vue";
import AgencySearchZone from "@/components/organism/agency-search-zone.vue";
import StoreItemList from "@/components/organism/store-item-list.vue";
import {ref} from "vue";

const isFullScreen = ref<boolean>(false)

const showFullScreenStore = () => {
  console.log('showFullScreenStore')
  isFullScreen.value = true
}
</script>

<template>
  <div class="trombino-app-homepage" :class="{'is-full-screen': isFullScreen}">
    <h1>Hello, Trombino Homepage</h1>
    <template v-if="!isFullScreen">
      <agency-search-zone class="mb-3" />
      <agency-map />
      <store-item-list
        class="mt-3"
        v-model="isFullScreen"
        @showMoreStore="showFullScreenStore"
      />
    </template>
    <template v-else>
      <div class="row">
        <div class="col-12 col-md-4">
          <agency-search-zone class="mb-3" />
          <store-item-list
            v-model="isFullScreen"
            class="mt-3"
          />
        </div>
        <div class="col-12 col-md-8">
          <agency-map />
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>

</style>
