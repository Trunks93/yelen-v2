<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useConversations } from '@/composables/useConversations'
import { useMessages } from '@/composables/useMessages'
import ConversationDrawer from './ConversationDrawer.vue'
import type { Conversation } from '@/domain/entity/chat'
import ChatWindow from "@/components/ChatWindow.vue";

const {
  conversations,
  loading: conversationsLoading,
  fetchConversations,
} = useConversations()

const activeConversation = ref<Conversation | null>(null)
const isChatWindowOpened = ref(false)
const isConversationDrawerOpen = ref(false)
const handleConversationSelect = (conversation: Conversation) => {
  activeConversation.value = conversation
  loadMessages(conversation.id)
}

const loadMessages = async (conversationId: number) => {
  const { fetchMessages } = useMessages(conversationId)
  await fetchMessages()
}

const handleConversationDrawer = (status: boolean) => {
  console.log('---handleConversationDrawer - status----', status)
  isConversationDrawerOpen.value = status
}

onMounted(async () => {
  await fetchConversations()
})
</script>

<template>
  <template v-if="!isChatWindowOpened">
    <button class="btn btn-primary chat-start-button" @click="isChatWindowOpened = true">Besoin de discussion ?</button>
  </template>
  <template v-else>
    <div class="chat-container">
      <ChatWindow
        :conversation="activeConversation"
        @toggleConversationDrawer="handleConversationDrawer"
      />

    <ConversationDrawer
      :conversations="conversations"
      :loading="conversationsLoading"
      :is-open="isConversationDrawerOpen"
      @select="handleConversationSelect"
      @toggleConversationDrawer="handleConversationDrawer"
    />
  </div>
  </template>
</template>

<style scoped>
.chat-start-button{
  bottom: 100px;
  position: relative;
}
.chat-container {
  width: 400px;
  max-width: 600px;
  margin: 0 auto;
  height: 600px;
  display: flex;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}
</style>
