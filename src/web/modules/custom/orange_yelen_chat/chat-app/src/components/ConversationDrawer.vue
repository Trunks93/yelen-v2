<script setup lang="ts">
import { ref } from 'vue'
import type { Conversation } from '@/domain/entity/chat'
import NotificationBadge from './NotificationBadge.vue'

const props = defineProps<{
  conversations: Conversation[]
  loading?: boolean
}>()

const emit = defineEmits<{
  (e: 'select', conversation: Conversation): void
}>()

const isOpen = ref(false)

const toggleDrawer = () => {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <div class="conversation-drawer" :class="{ open: isOpen }">
    <button class="btn btn-primary drawer-toggle" @click="toggleDrawer">
      {{ isOpen ? '×' : 'Mes Conversations' }}
      <NotificationBadge v-if="!isOpen" />
    </button>

    <div class="drawer-content">
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
        @click="emit('select', conv)"
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
  </div>
</template>

<style scoped>
.conversation-drawer {
  position: fixed;
  top: 0;
  right: 0;
  height: 100vh;
  background-color: white;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
  transform: translateX(100%);
  transition: transform 0.3s ease;
  z-index: 1000;
}

.conversation-drawer.open {
  transform: translateX(0);
}

.drawer-toggle {
  position: absolute;
  left: -550px;
  bottom: 50px;
  padding: 8px 16px;
  border: none;
}

.drawer-content {
  width: 300px;
  height: 100%;
  overflow-y: auto;
}

/* ... Reste des styles du ConversationList original ... */
</style>
