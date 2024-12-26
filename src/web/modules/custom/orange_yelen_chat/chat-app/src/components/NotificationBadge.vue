<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const unreadCount = ref(0)
let checkInterval: ReturnType<typeof setInterval>

const checkUnreadMessages = async () => {
  try {
    const response = await fetch('/yelen-chat/api/conversations')
    const conversations = await response.json()

    let count = 0
    for (const conv of conversations) {
      const messagesResponse = await fetch(`/yelen-chat/api/conversations/${conv.id}/messages`)
      const messages = await messagesResponse.json()
      count += messages.filter((m: any) => !m.read && m.user_id !== window.drupalSettings.yelen_chat.current_user.uid).length
    }

    unreadCount.value = count

    // Notification du navigateur si de nouveaux messages
    if (count > 0 && Notification.permission === 'granted') {
      new Notification('Nouveau(x) message(s)', {
        body: `Vous avez ${count} message(s) non lu(s)`,
        icon: '/themes/custom/your_theme/images/chat-icon.png'
      })
    }
  } catch (error) {
    console.error('Erreur lors de la vérification des messages non lus:', error)
  }
}

const requestNotificationPermission = async () => {
  if (Notification.permission === 'default') {
    await Notification.requestPermission()
  }
}

onMounted(() => {
  requestNotificationPermission()
  checkUnreadMessages()
  // checkInterval = setInterval(checkUnreadMessages, 10000)
})

onUnmounted(() => {
  // clearInterval(checkInterval)
})
</script>

<template>
  <div class="notification-badge" v-if="unreadCount > 0">
    {{ unreadCount }}
  </div>
</template>

<style scoped>
.notification-badge {
  background-color: #ff4444;
  color: white;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 0.8em;
  position: absolute;
  top: -8px;
  right: -8px;
  min-width: 18px;
  text-align: center;
}</style>
