<script setup lang="ts">
const emits = defineEmits<{
  (e: 'clickTab', tab: 'profile' | 'documents'): void
}>()

const props = defineProps<{
  activeTab: 'profile' | 'documents'
}>()

const handleTabClick = (tab: 'profile' | 'documents') => {
  emits('clickTab', tab)
}

</script>

<template>
  <div class="border-b border-gray-300">
    <ul class="flex text-sm font-medium text-center text-gray-600">
      <li class="mr-2">
        <button
          @click="handleTabClick('profile')"
          :class="[
            'flex items-center p-4 border-b-2 rounded-t-lg',
            props.activeTab === 'profile'
              ? 'text-blue-600 border-blue-600'
              : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-600'
          ]"
        >
          Profile
        </button>
      </li>
      <li class="mr-2">
        <button
          @click="handleTabClick('documents')"
          :class="[
            'flex items-center p-4 border-b-2 rounded-t-lg',
            props.activeTab === 'documents'
              ? 'text-blue-600 border-blue-600'
              : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-600'
          ]"
        >
          Documents
        </button>
      </li>
    </ul>
  </div>
</template>