<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useConversations } from '@/composables/useConversations'
import { useMessages } from '@/composables/useMessages'
import MessageInput from './MessageInput.vue'
import MessageList from './MessageList.vue'
import ConversationDrawer from './ConversationDrawer.vue'
import OnlineStatus from './OnlineStatus.vue'
import type { Conversation } from '@/domain/entity/chat'

// Récupérer l'utilisateur courant depuis drupalSettings
const currentUser = window.drupalSettings?.yelen_chat?.current_user
console.log('---- current User----', currentUser)
const {
  conversations,
  loading: conversationsLoading,
  lastConversation,
  fetchConversations,
  createConversation
} = useConversations()

const activeConversation = ref<Conversation | null>(null)
const messages = ref([])
const messageLoading = ref(false)
const isChatWindowOpened = ref(false)

const handleConversationSelect = (conversation: Conversation) => {
  activeConversation.value = conversation
  loadMessages(conversation.id)
}

const loadMessages = async (conversationId: number) => {
  const { messages: conversationMessages, loading, fetchMessages } = useMessages(conversationId)
  await fetchMessages()
  console.log('----loadMessages - loading----', loading.value)
  console.log('----loadMessages - conversationMessages----', conversationMessages.value)
  messages.value = conversationMessages.value
  messageLoading.value = loading.value
  console.log('----loadMessages - messageLoading----', messageLoading.value)
}

const handleSendMessage = async (message: string) => {
  console.log('----handleSendMessage----', message)
  console.log('----handleSendMessage - activeConversation----', activeConversation.value)
  if (!activeConversation.value) {
    console.log('---Not active conversation----')
    console.log('---We should create one----')
    const conversationResponse = await createConversation(currentUser.uid)
    console.log('----conversationResponse----', conversationResponse)
    if(conversationResponse && typeof conversationResponse === "object" && "id" in conversationResponse){
      activeConversation.value = conversationResponse
    }
  }

  const { sendMessage } = useMessages(activeConversation.value.id)
  await sendMessage(message)
}

onMounted(async () => {
  await fetchConversations()
  if (lastConversation.value) {
    activeConversation.value = lastConversation.value
    await loadMessages(lastConversation.value.id)
  }
})
</script>

<template>
  <template v-if="!isChatWindowOpened">
    <button class="btn btn-primary chat-start-button" @click="isChatWindowOpened = true">Besoin de discussion ?</button>
  </template>
  <template v-else>
    <div class="chat-container">
    <div class="chat-window">
      <div v-if="activeConversation" class="chat-header">
        <div class="user-info">
          <h3>{{ activeConversation.other_user.name }}</h3>
          <OnlineStatus :userId="activeConversation.other_user.uid" />
        </div>
        <button
          v-if="activeConversation.status === 'active'"
          @click="closeConversation"
          class="close-btn"
        >
          Clôturer la conversation
        </button>
        <span v-else class="closed-status">Conversation clôturée</span>
      </div>

      <MessageList
        :messages="messages"
        :currentUserId="currentUser?.uid"
      />

      <MessageInput
        @send="handleSendMessage"
        :disabled="messageLoading || (activeConversation?.status === 'closed')"
      />
    </div>

    <ConversationDrawer
      :conversations="conversations"
      :loading="conversationsLoading"
      @select="handleConversationSelect"
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
  max-width: 1200px;
  margin: 0 auto;
  height: 600px;
  display: flex;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}

.chat-window {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.chat-header {
  padding: 15px;
  background-color: #f5f5f5;
  border-bottom: 1px solid #ddd;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.close-btn {
  padding: 6px 12px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.closed-status {
  color: #666;
  font-style: italic;
}
</style>
