<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
import type { Message, Conversation } from '@/domain/entity/chat'
import OnlineStatus from './OnlineStatus.vue'

const props = defineProps<{
  conversation: Conversation
}>()

const messages = ref<Message[]>([])
const newMessage = ref('')
const loading = ref(false)
const currentUserId = ref(window.drupalSettings.yelen_chat.currentUser.uid)

const fetchMessages = async () => {
  if (!props.conversation) return

  try {
    const response = await fetch(`/yelen-chat/api/conversations/${props.conversation.id}/messages`)
    messages.value = await response.json()

    // Marquer les messages comme lus
    if (messages.value.length > 0) {
      await fetch(`/yelen-chat/api/conversations/${props.conversation.id}/messages/read`, {
        method: 'POST'
      })
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des messages:', error)
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim() || !props.conversation) return

  try {
    loading.value = true
    await fetch(`/yelen-chat/api/conversations/${props.conversation.id}/messages`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ message: newMessage.value }),
    })

    newMessage.value = ''
    await fetchMessages()
  } catch (error) {
    console.error('Erreur lors de l\'envoi du message:', error)
  } finally {
    loading.value = false
  }
}

const closeConversation = async () => {
  if (!props.conversation) return

  try {
    await fetch(`/yelen-chat/api/conversations/${props.conversation.id}/close`, {
      method: 'POST',
    })
    emit('conversationClosed')
  } catch (error) {
    console.error('Erreur lors de la clôture de la conversation:', error)
  }
}

const isOwnMessage = (message: Message) => message.user_id === currentUserId.value

let refreshInterval: ReturnType<typeof setInterval>

onMounted(() => {
  fetchMessages()
  refreshInterval = setInterval(fetchMessages, 5000)
})

onUnmounted(() => {
  clearInterval(refreshInterval)
})

watch(() => props.conversation, fetchMessages)

const emit = defineEmits<{
  (e: 'conversationClosed'): void
}>()
</script>

<template>
  <div class="chat-window">
    <div class="chat-header">
      <div class="user-info">
        <h3>{{ conversation.other_user.name }}</h3>
        <OnlineStatus :userId="conversation.other_user.uid" />
      </div>
      <button
        v-if="conversation.status === 'active'"
        @click="closeConversation"
        class="close-btn"
      >
        Clôturer la conversation
      </button>
      <span v-else class="closed-status">Conversation clôturée</span>
    </div>

    <div class="messages-container">
      <div
        v-for="message in messages"
        :key="message.id"
        class="message-wrapper"
        :class="{ 'own-message': isOwnMessage(message) }"
      >
        <div class="message" :class="{ 'own': isOwnMessage(message) }">
          <div class="message-header">
            <span class="username">{{ message.username }}</span>
            <span class="timestamp">{{ new Date(message.created * 1000).toLocaleString() }}</span>
          </div>
          <div class="message-content">
            {{ message.message }}
          </div>
        </div>
      </div>
    </div>

    <div class="message-input" v-if="conversation.status === 'active'">
      <input
        v-model="newMessage"
        @keyup.enter="sendMessage"
        :disabled="loading"
        placeholder="Écrivez votre message..."
        type="text"
      >
      <button @click="sendMessage" :disabled="loading">
        {{ loading ? 'Envoi...' : 'Envoyer' }}
      </button>
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

.messages-container {
  flex-grow: 1;
  overflow-y: auto;
  padding: 10px;
  margin-bottom: 20px;
  background-color: #e5ddd5;
}

.message-wrapper {
  display: flex;
  margin-bottom: 10px;
  justify-content: flex-start;
}

.message-wrapper.own-message {
  justify-content: flex-end;
}

.message {
  max-width: 70%;
  padding: 8px 12px;
  background-color: #ffffff;
  border-radius: 8px;
  position: relative;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.message.own {
  background-color: #dcf8c6;
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
  font-size: 0.8em;
}

.username {
  font-weight: bold;
  color: #2c3e50;
}

.timestamp {
  color: #666;
  margin-left: 8px;
}

.message-content {
  color: #303030;
  word-wrap: break-word;
}

.message-input {
  display: flex;
  gap: 10px;
  background-color: #f0f0f0;
  padding: 10px;
  border-radius: 24px;
  margin: 10px;
}

input {
  flex-grow: 1;
  padding: 12px;
  border: none;
  border-radius: 24px;
  background-color: white;
  font-size: 1em;
}

input:focus {
  outline: none;
  box-shadow: 0 0 0 2px #25d366;
}

button {
  padding: 8px 20px;
  background-color: #25d366;
  color: white;
  border: none;
  border-radius: 24px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #128c7e;
}

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
