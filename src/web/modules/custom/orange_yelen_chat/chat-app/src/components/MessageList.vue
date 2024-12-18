<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import type { Message } from '@/domain/entity/chat'

const props = defineProps<{
  messages: Message[]
  currentUserId: number
}>()

const messagesContainer = ref<HTMLDivElement | null>(null)

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

const isOwnMessage = (message: Message) => message.user_id === props.currentUserId

onMounted(scrollToBottom)
watch(() => props.messages, scrollToBottom, { deep: true })
</script>

<template>
  <div class="messages-container" ref="messagesContainer">
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
</template>

<style scoped>
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
  background-color: var(--bs-primary);
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
  color: #2d2d2d;
}

.timestamp {
  color: #FFFFFF;
  margin-left: 8px;
}

.message-content {
  color: #000000;
  word-wrap: break-word;
}
</style>
