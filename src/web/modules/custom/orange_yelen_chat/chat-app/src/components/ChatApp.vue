<script setup lang="ts">
import {ref} from "vue"
import ChatWindow from "@/components/ChatWindow.vue";
const isChatWindowOpened = ref(false)
</script>

<template>
<div>
  <template v-if="!isChatWindowOpened">
    <button class="btn btn-primary chat-start-button" @click="isChatWindowOpened = true">Besoin de discussion</button>
  </template>
  <template v-else>
    <chat-window :class="{'is-open': isChatWindowOpened}" />
  </template>
</div>
</template>

<style scoped>
.chat-start-button{
  bottom: 100px;
  position: relative;
}
</style>
