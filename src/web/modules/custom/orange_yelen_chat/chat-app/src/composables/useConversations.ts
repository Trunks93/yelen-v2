import { ref, computed } from 'vue'
import type { Conversation } from '@/types'

export function useConversations() {
  const conversations = ref<Conversation[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const hasConversations = computed(() => conversations.value.length > 0)
  const lastConversation = computed(() => {
    if (!conversations.value.length) return null
    return conversations.value[0] // Les conversations sont déjà triées par date
  })

  const fetchConversations = async () => {
    try {
      loading.value = true
      const response = await fetch('/yelen-chat/api/conversations')
      conversations.value = await response.json()
    } catch (err) {
      error.value = 'Erreur lors de la récupération des conversations'
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  const createConversation = async (participantId: number) => {
    try {
      loading.value = true
      const response = await fetch('/yelen-chat/api/conversations', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ participant_id: participantId }),
      })
      const newConversation = await response.json()
      conversations.value.unshift(newConversation)
      return newConversation
    } catch (err) {
      error.value = 'Erreur lors de la création de la conversation'
      console.error(err)
      return null
    } finally {
      loading.value = false
    }
  }
  const closeConversation = async (conversationId: number, participantId: number) => {
    try {
      loading.value = true
      const response = await fetch(`/yelen-chat/api/conversations/${conversationId}/close`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ participant_id: participantId }),
      })
      const newConversation = await response.json()
      conversations.value.unshift(newConversation)
      return newConversation
    } catch (err) {
      error.value = 'Erreur lors de la création de la conversation'
      console.error(err)
      return null
    } finally {
      loading.value = false
    }
  }

  return {
    conversations,
    loading,
    error,
    hasConversations,
    lastConversation,
    fetchConversations,
    createConversation,
    closeConversation
  }
}
