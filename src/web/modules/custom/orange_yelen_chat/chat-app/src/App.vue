<script setup lang="ts">
console.log('From App.vue - Chat App')
import ChatApp from "@/components/ChatApp.vue";
</script>

<template>
  <main class="chat-app">
    <chat-app />
  </main>
</template>

<style scoped>
.chat-app{
  position: fixed;
  bottom: 0;
  right: 0;
}
</style>
