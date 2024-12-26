<script setup lang="ts">
import {ref, onMounted, onUnmounted, watch, type PropType, onBeforeMount} from 'vue'
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

const messages = ref<Message[]>([])
const messageLoading = ref(false)
const activeConversation = ref<Conversation | null>(null)
const isMessagesInit = ref(false)
const loadMessages = async (conversationId: number) => {
  const { messages: conversationMessages, loading, fetchMessages } = useMessages(conversationId)
  await fetchMessages()
  messages.value = conversationMessages.value
  messageLoading.value = loading.value
}
const handleSendMessage = async (message: string) => {
  if (!activeConversation.value) {
    const conversationResponse = await createConversation(currentUser.uid)
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

const fetchMessages = async() => {
  const intervalTimeout = isMessagesInit.value ? 5000 : 100
  setInterval(async () => {
    await fetchConversations()
    if (lastConversation.value) {
      activeConversation.value = lastConversation.value
      await loadMessages(lastConversation.value.id)
    }
  }, intervalTimeout)
}
onMounted(async () => {
  activeConversation.value = conversation
  await fetchMessages()
  isMessagesInit.value = true
})
</script>

<template>
  <div class="chat-window">
    <div class="chat-header">
      <div v-if="activeConversation" class="user-info">
        <h3 class="mb-0">{{ activeConversation.other_user ?  activeConversation.other_user.name : 'Administrateur' }}</h3>
        <OnlineStatus class="d-none" :userId="activeConversation.other_user?.uid" />
      </div>
      <div v-if="activeConversation && activeConversation.status === 'closed'" class="conversation-status">
        <span class="badge rounded-pill text-bg-danger">Clôturée</span>
      </div>
      <button
        v-if="activeConversation && activeConversation.status === 'active'"
        class="btn btn-sm btn-danger"
        data-bs-toggle="modal" data-bs-target="#closeConversationModal"
      >Clôturer</button>
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

    <div class="modal fade" id="closeConversationModal" tabindex="-1" aria-labelledby="closeConversationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title h3" id="closeConversationModalLabel">Confirmation</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Fermer"><span class="visually-hidden">Fermer</span></button>
          </div>
          <div class="modal-body">
            <h5>Voulez-vous vraiment clôturer cette conversation ?</h5>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" @click="handleCloseConversation" data-bs-dismiss="modal">Oui, je clôture</button>
          </div>
        </div>
      </div>
    </div>
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
