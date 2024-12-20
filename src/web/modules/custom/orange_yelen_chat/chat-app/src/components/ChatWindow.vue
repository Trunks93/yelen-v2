<script setup lang="ts">
import {ref, onMounted, onUnmounted, watch, type PropType} from 'vue'
import type { Message, Conversation } from '@/domain/entity/chat'
import OnlineStatus from './OnlineStatus.vue'
import MessageInput from "@/components/MessageInput.vue";
import MessageList from "@/components/MessageList.vue";
import {useMessages} from "@/composables/useMessages";
import {useConversations} from "@/composables/useConversations";

const {conversation} = defineProps<{
  conversation: Conversation | null
}>()
const emit = defineEmits<{
  (e: 'conversationClosed'): void,
  (e: 'openConversationDrawer'): void
}>()
const {
  loading: conversationsLoading,
  lastConversation,
  fetchConversations,
  createConversation,
  closeConversation
} = useConversations()
// Récupérer l'utilisateur courant depuis drupalSettings
const currentUser = window.drupalSettings?.yelen_chat?.current_user
console.log('---- current User----', currentUser)

const messages = ref<Message[]>([])
const messageLoading = ref(false)
const activeConversation = ref<Conversation | null>(null)

const loadMessages = async (conversationId: number) => {
  const { messages: conversationMessages, loading, fetchMessages } = useMessages(conversationId)
  await fetchMessages()
  messages.value = conversationMessages.value
  messageLoading.value = loading.value
}
const handleSendMessage = async (message: string) => {
  if (!activeConversation.value) {
    console.log('---Not active conversation----')
    console.log('---We should create one----')
    const conversationResponse = await createConversation(currentUser.uid)
    console.log('----conversationResponse----', conversationResponse)
    if(conversationResponse && typeof conversationResponse === "object" && "id" in conversationResponse){
      activeConversation.value = conversationResponse
    }
  }

  if(activeConversation.value){
    const { sendMessage } = useMessages(activeConversation.value.id)
    await sendMessage(message)
    await loadMessages(activeConversation.value.id)
  }
}
const handleCloseConversation = async() => {
  if(!activeConversation.value) return;
  console.log('----Close Conversation---')
  const closeConversationResponse = await closeConversation(activeConversation.value.id, currentUser?.uid)

  if(
    closeConversationResponse &&
    typeof closeConversationResponse === "object" &&
    "status" in closeConversationResponse &&
    closeConversationResponse.status === "success"
  ) {
    activeConversation.value.status = "closed"
    return;
  }
}

onMounted(async () => {
  activeConversation.value = conversation
  await fetchConversations()
  if (lastConversation.value) {
    activeConversation.value = lastConversation.value
    await loadMessages(lastConversation.value.id)
  }
})
</script>

<template>
  <div class="chat-window">
    <div v-if="activeConversation" class="chat-header">
      <div class="user-info">
        <h3>{{ activeConversation.other_user ?  activeConversation.other_user.name : 'Administrateur' }}</h3>
        <OnlineStatus class="d-none" :userId="activeConversation.other_user?.uid" />
      </div>
      <div class="btn-group">
        <button class="btn btn-dropdown btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Actions
        </button>
        <ul class="dropdown-menu">
          <li v-if="activeConversation.status === 'active'"><a class="dropdown-item" href="#" @click="handleCloseConversation">Clôturer cette conversation</a></li>
          <li><a class="dropdown-item" href="#" @click="$emit('openConversationDrawer')">Mes conversations</a></li>
        </ul>
      </div>
    </div>

    <MessageList
      :messages="messages"
      :currentUserId="currentUser?.uid"
    />

    <MessageInput
      v-if="activeConversation?.status !== 'closed'"
      @send="handleSendMessage"
      :disabled="messageLoading"
    />
  </div>
</template>

<style scoped>
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
