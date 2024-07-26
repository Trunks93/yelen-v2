<script setup lang="ts">
import {ref} from "vue";
import ServiceListItem from "@/components/atom/serviceListItem.vue"

const serviceListItems = ['Services Orange Money', 'Produits Fixe, Internet et Mobile', 'SAV', 'Services Fixe, Mobile et Internet']
const servicesChecked = ref<number[]>([])
const setCheckedServices = (index: number) => {
  console.log('servicesChecked.push(index)')
  if(isChecked(index)){
    servicesChecked.value = servicesChecked.value.filter(service => service !== index)
    return;
  }
  servicesChecked.value.push(index)
}
const isChecked = (index: number) => servicesChecked.value.includes(index)
</script>

<template>
    <div class="service-list">
        <h3 class="title">Services disponibles</h3>
        <ul class="list-unstyled">
            <service-list-item
              class="item"
            v-for="(item, index) in serviceListItems"
            :name="item"
            :is-checked="isChecked(index)"
            :key='`service-item-${index}`'
            @click="setCheckedServices(index)"
            />
        </ul>
    </div>
</template>

<style scoped>
.service-list{
  overflow-x: scroll;
  .title{
    font-size: 1.2rem;
  }
  ul{
    margin-left: 0;
    display: flex;
    gap: 10px;
  }
}
</style>
