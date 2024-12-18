<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
  disabled?: boolean
}>()

const emit = defineEmits<{
  (e: 'send', message: string): void
}>()

const message = ref('')

const handleSubmit = () => {
  if (!message.value.trim()) return
  emit('send', message.value)
  message.value = ''
}
</script>

<template>
  <div class="message-input">
    <input
      v-model="message"
      @keyup.enter="handleSubmit"
      :disabled="disabled"
      placeholder="Écrivez votre message..."
      type="text"
    >
    <button class="btn btn-primary" @click="handleSubmit" :disabled="disabled || !message.trim()">
      Envoyer
    </button>
  </div>
</template>

<style scoped>
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
  box-shadow: 0 0 0 2px var(--bs-primary);
}

button {
  padding: 8px 20px;
  border: none;
  border-radius: 24px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.2s;
}

button:hover:not(:disabled) {
  background-color: #000000;
}

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
