<script setup lang="ts">
import StoreItem from "@/components/molecule/store-item.vue";
import {onMounted} from "vue";
const isFullScreen = defineModel()
const emit = defineEmits(['showMoreStore'])
const storeList = [
  {
    name: 'Agence Orange-COCODY',
    opening:{
      startHour: '08:00',
      closeHour: '17:00'
    },
    address: 'Cocody près de la cité rouge, ABIDJAN',
    services: [
      {
        id: 1,
        name: 'Produits fixes, mobiles et internet'
      },
      {
        id: 2,
        name: 'Services fixes, mobiles et internet'
      },
      {
        id: 3,
        name: 'Services S.A.V'
      },
    ]
  },
  {
    name: 'Agence Orange-ANGRE',
    opening:{
      startHour: '07:00',
      closeHour: '20:00'
    },
    address: 'Cocody près de la cité rouge, ABIDJAN',
    services: [
      {
        id: 1,
        name: 'Produits fixes, mobiles et internet'
      },
      {
        id: 2,
        name: 'Services fixes, mobiles et internet'
      },
      {
        id: 3,
        name: 'Services S.A.V'
      },
    ]
  },
  {
    name: 'Agence Orange-MARCORY',
    opening:{
      startHour: '08:00',
      closeHour: '16:25'
    },
    address: 'Cocody près de la cité rouge, ABIDJAN',
    services: [
      {
        id: 1,
        name: 'Produits fixes, mobiles et internet'
      },
      {
        id: 2,
        name: 'Services fixes, mobiles et internet'
      },
      {
        id: 3,
        name: 'Services S.A.V'
      },
    ]
  },
]
onMounted(() => {
  console.log('isFullScreen', isFullScreen.value)
})
const showMoreStore = () => {
  if(!isFullScreen.value){
    emit('showMoreStore')
    return;
  }

  console.log('xxx Show More store xxxx')
}
</script>

<template>
  <section>
    <store-item
      v-for="store in storeList"
      :key="`store-${store.name}`"
      :store="store"
      class="mb-3"
    />
    <div class="text-center">
      <button
        class="btn btn-outline-secondary"
        @click="showMoreStore()"
      >Voir plus</button>
    </div>
  </section>
</template>

<style scoped>

</style>
