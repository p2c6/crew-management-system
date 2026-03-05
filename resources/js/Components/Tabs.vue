<script setup lang="ts">
import { ref, computed } from 'vue'

const emits = defineEmits<{
  (e: 'clickTab', tab: 'profile' | 'documents'): void
}>()

const current = ref('profile');

const handleTabClick = (tab: 'profile' | 'documents') => {
    emits('clickTab', tab)
    current.value =  tab
}

</script>

<template>
  <div class="border-b border-gray-300">
    <ul class="flex text-sm font-medium text-center text-gray-600">
      <li class="mr-2">
        <button
          @click="handleTabClick('profile')"
          :class="[
            'flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200',
            current === 'profile' ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-600'
          ]"
        >
          Profile
        </button>
      </li>
      <li class="mr-2">
        <button
          @click="handleTabClick('documents')"
          :class="[
            'flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200',
            current === 'documents' ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-600'
          ]"
        >
          Documents
        </button>
      </li>
    </ul>
  </div>
</template>