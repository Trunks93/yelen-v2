<script setup lang="ts">
import {onMounted, ref, watch} from 'vue'
import type { Conversation } from '@/domain/entity/chat'
import NotificationBadge from './NotificationBadge.vue'

const props = defineProps<{
  conversations: Conversation[]
  isOpen?: boolean,
  loading?: boolean
}>()

const emit = defineEmits<{
  (e: 'select', conversation: Conversation): void,
  (e: 'toggleConversationDrawer', status: boolean): void
}>()

const toggleDrawer = () => {
  emit('toggleConversationDrawer', !props.isOpen)
}

watch(() => props.isOpen, (currentValue, oldValue) => {
  console.log('---Watch props.isOpen----', currentValue)
  // isOpen.value = currentValue
})
</script>

<template>
  <div class="conversation-drawer" :class="{ open: isOpen }">
    <button class="btn btn-primary drawer-toggle" @click="toggleDrawer">
      {{ isOpen ? '×' : '' }}
      <NotificationBadge v-if="!isOpen" />
    </button>

    <div class="drawer-content">
      <div class="conversation-header">
        <h2>Conversations ({{conversations.length}})</h2>
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
          <strong>{{ conv.other_user ? conv.other_user.name : 'Administrateur' }}</strong>
          <span class="status" v-if="conv.status === 'closed'">(Clôturée)</span>
        </div>
        <div class="timestamp" v-if="conv.updated">
          {{ new Date(conv.updated * 1000).toLocaleString() }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.conversation-drawer {
  position: fixed;
  right: 0;
  height: 600px;
  max-width: 1200px;
  background-color: white;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
  transform: translateX(100%);
  transition: transform 0.3s ease;
  z-index: 1000;
  padding: 1rem;
}

.conversation-drawer.open {
  transform: translateX(0);
}

.drawer-toggle {
  position: absolute;
  right: 10px;
  top: 10px;
  padding: 8px 16px;
  border: none;
  border-radius: 50%;
  width: 48px;
  height: 48px;
}

.drawer-content {
  width: 300px;
  height: 100%;
  overflow-y: auto;
}

/* ... Reste des styles du ConversationList original ... */
</style>
