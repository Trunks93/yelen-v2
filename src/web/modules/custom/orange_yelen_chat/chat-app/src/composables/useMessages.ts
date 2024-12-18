import { ref } from 'vue'
import type { Message } from '@/types'

export function useMessages(conversationId: number) {
  const messages = ref<Message[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchMessages = async () => {
    try {
      loading.value = true
      const response = await fetch(`/yelen-chat/api/conversations/${conversationId}/messages`)
      messages.value = await response.json()
      // Marquer les messages comme lus
      await fetch(`/yelen-chat/api/conversations/${conversationId}/messages/read`, {
        method: 'POST'
      })
    } catch (err) {
      error.value = 'Erreur lors de la récupération des messages'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  const sendMessage = async (message: string) => {
    try {
      loading.value = true
      const response = await fetch(`/yelen-chat/api/conversations/${conversationId}/messages`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message }),
      })
      const newMessage = await response.json()
      messages.value.push(newMessage)
      return newMessage
    } catch (err) {
      error.value = 'Erreur lors de l\'envoi du message'
      console.error(err)
      return null
    } finally {
      loading.value = false
    }
  }

  return {
    messages,
    loading,
    error,
    fetchMessages,
    sendMessage,
  }
}
