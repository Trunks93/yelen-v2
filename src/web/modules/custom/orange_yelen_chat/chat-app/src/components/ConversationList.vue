<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Conversation } from '@/domain/entity/chat'
import NotificationBadge from './NotificationBadge.vue'

const conversations = ref<Conversation[]>([])
const loading = ref(false)

const emit = defineEmits<{
  (e: 'select', conversation: Conversation): void
}>()

const fetchConversations = async () => {
  try {
    loading.value = true
    const response = await fetch('/yelen-chat/api/conversations')
    conversations.value = await response.json()
  } catch (error) {
    console.error('Erreur lors de la récupération des conversations:', error)
  } finally {
    loading.value = false
  }
}

const selectConversation = (conversation: Conversation) => {
  emit('select', conversation)
}

onMounted(fetchConversations)
</script>

<template>
  <div class="conversation-list">
    <div class="conversation-header">
      <h2>Conversations</h2>
      <NotificationBadge />
    </div>
    <div v-if="loading" class="loading">
      Chargement...
    </div>
    <div v-else-if="conversations.length === 0" class="empty">
      Aucune conversation
    </div>
    <div
      v-else
      v-for="conv in conversations"
      :key="conv.id"
      class="conversation-item"
      :class="{ closed: conv.status === 'closed' }"
      @click="selectConversation(conv)"
    >
      <div class="user-info">
        <strong>{{ conv.other_user.name }}</strong>
        <span class="status" v-if="conv.status === 'closed'">(Clôturée)</span>
      </div>
      <div class="timestamp">
        {{ new Date(conv.updated * 1000).toLocaleString() }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.conversation-list {
  width: 300px;
  border-right: 1px solid #ddd;
  height: 100%;
  overflow-y: auto;
}

.conversation-header {
  padding: 15px;
  background-color: #f5f5f5;
  border-bottom: 1px solid #ddd;
  position: relative;
}

.conversation-item {
  padding: 15px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  transition: background-color 0.2s;
}

.conversation-item:hover {
  background-color: #f5f5f5;
}

.conversation-item.closed {
  opacity: 0.7;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 8px;
}

.status {
  color: #666;
  font-size: 0.9em;
}

.timestamp {
  font-size: 0.8em;
  color: #666;
  margin-top: 4px;
}

.loading, .empty {
  padding: 20px;
  text-align: center;
  color: #666;
}
</style>
