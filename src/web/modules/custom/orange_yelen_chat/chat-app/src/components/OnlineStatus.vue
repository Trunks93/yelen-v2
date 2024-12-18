<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps<{
  userId: number
}>()

const isOnline = ref(false)
let statusInterval: ReturnType<typeof setInterval>

const updateOnlineStatus = async () => {
  try {
    await fetch('/yelen-chat/api/chat/online-status', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ status: 'online' }),
    })
  } catch (error) {
    console.error('Erreur lors de la mise à jour du statut:', error)
  }
}

onMounted(() => {
  updateOnlineStatus()
  statusInterval = setInterval(updateOnlineStatus, 30000)
})

onUnmounted(() => {
  clearInterval(statusInterval)
})
</script>

<template>
  <div class="online-status">
    <span
      class="status-indicator"
      :class="{ online: isOnline }"
    ></span>
    {{ isOnline ? 'En ligne' : 'Hors ligne' }}
  </div>
</template>

<style scoped>
.online-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.9em;
  color: #666;
}

.status-indicator {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #ccc;
}

.status-indicator.online {
  background-color: var(--bs-primary);
}</style>
