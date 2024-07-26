<script setup lang="ts">
import {computed, onMounted, PropType} from "vue";
type StoreItemType = {
  name: string
  opening: {
    startHour: string
    closeHour: string
  }
  address: string
  services: Array<{
    id: number
    name: string
  }>
}
const {store} = defineProps({
  store: {
    type: Object as PropType<StoreItemType>,
    required: true
  }
})
const openingAreDefined = computed(() => {
  return store?.opening.startHour.length > 0 && store?.opening.closeHour.length > 0
})
const isOpen = computed(() => {
  const now = new Date();
  const openingStartDate = new Date();
  const openingCloseDate = new Date();

  const storeOpeningStartHourParsed = store.opening.startHour.split(':')
  const storeOpeningCloseHourParsed = store.opening.closeHour.split(':')
  openingStartDate.setHours(Number(storeOpeningStartHourParsed[0]), Number(storeOpeningStartHourParsed[1]) ?? 0, 0)
  openingCloseDate.setHours(Number(storeOpeningCloseHourParsed[0]), Number(storeOpeningCloseHourParsed[1]) ?? 0, 0)

  return now.getTime() >= openingStartDate.getTime() && now.getTime() <= openingCloseDate.getTime()
})

const openingStartHour = computed(() => {
  const storeOpeningStartHourParsed = store.opening.startHour.split(':')
  return `${storeOpeningStartHourParsed[0]}h${storeOpeningStartHourParsed[1] ?? '00'}`
})
const openingCloseHour = computed(() => {
  const storeOpeningCloseHourParsed = store.opening.closeHour.split(':')
  return `${storeOpeningCloseHourParsed[0]}h${storeOpeningCloseHourParsed[1] ?? '00'}`
})

onMounted(() => {
  console.log('Props store', store)
  const currentDate = new Date();
  console.log({currentDate})
  if(store.opening.startHour && store.opening.startHour.length > 0){
    const storeOpeningStartHourParsed = store.opening.startHour.split(':')
    currentDate.setHours(Number(storeOpeningStartHourParsed[0]), Number(storeOpeningStartHourParsed[1]) ?? 0, 0)
    console.log('start hour', {currentDate})
  }
  if(store.opening.closeHour && store.opening.closeHour.length > 0){
    const storeOpeningCloseHourParsed = store.opening.closeHour.split(':')
    currentDate.setHours(Number(storeOpeningCloseHourParsed[0]), Number(storeOpeningCloseHourParsed[1]) ?? 0, 0)
    console.log('close hour', {currentDate})
  }
})
</script>

<template>
  <article class="store-item">
    <div class="inner-content">
      <h4 class="name">{{ store.name }}</h4>
      <div class="opening mb-2" v-if="openingAreDefined">
        <span class="fw-bold">{{ isOpen ? 'Ouvert' : 'Fermé' }}</span>
        <span v-if="isOpen">Ferme à {{ openingCloseHour }}</span>
        <span v-else>Ouvre à {{ openingStartHour }}</span>
      </div>
      <address class="mb-2">
        <span>{{ store.address }}</span>
      </address>
      <div class="services">
        <ul class="list-unstyled list-inline">
          <li
            v-for="service in store.services"
            :key="`service-${service.id}`"
            class="list-inline-item"
          >
            <span>{{ service.name }}</span>
          </li>
        </ul>
      </div>
      <button class="btn btn-primary mt-3">Plus d'informations</button>
    </div>
  </article>
</template>

<style scoped>
.store-item{
  cursor: pointer;
  padding: 15px;
  transition: all .3s ease-in;
  border-bottom: 1px solid #ccc;
  &:hover{
    background-color: #000000;
    color: #FFFFFF;
    transition: all .3s ease-in;

    .btn-primary{
      background-color: transparent;
      border-color: #FFF;
      color: #FFFFFF;
    }
  }
  .opening{
    display: flex;
    gap: 10px;
  }
  address {
    font-style: normal;
  }
  .services{
    ul{
      margin-left: 0;
    }
  }
}
</style>
