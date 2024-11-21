<script setup lang="ts">
import {ref} from "vue"
import ChatWindow from "@/components/ChatWindow.vue";
import ConversationList from "@/components/ConversationList.vue";
import type { Conversation } from '@/domain/entity/chat'

const selectedConversation = ref<Conversation | null>(null)
const handleConversationSelect = (conversation: Conversation) => {
  selectedConversation.value = conversation
}

const handleConversationClosed = () => {
  selectedConversation.value = null
}
const isChatWindowOpened = ref(false)
</script>

<template>
<div>
  <template v-if="!isChatWindowOpened">
    <button class="btn btn-primary chat-start-button" @click="isChatWindowOpened = true">Besoin de discussion</button>
  </template>
  <template v-else>
    <div class="chat-container">
      <ConversationList @select="handleConversationSelect" />
      <ChatWindow
        v-if="selectedConversation"
        :conversation="selectedConversation"
        @conversation-closed="handleConversationClosed"
      />
    </div>
  </template>
</div>
</template>

<style scoped>
.chat-start-button{
  bottom: 100px;
  position: relative;
}
</style>
