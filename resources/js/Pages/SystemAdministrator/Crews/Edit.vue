<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import EditCrewForm from './Partials/EditCrewForm.vue'
import { Crew } from '@/types/Crew'
import { computed, ref } from 'vue'
import Tabs from '@/Components/Tabs.vue'
import DocumentList from './Partials/Document/DocumentList.vue'

defineProps<{
  crew: Crew
  ranks: Object,
}>()

const activeTab = ref<'profile' | 'documents'>('profile')

const handleClickTab = (tab: 'profile' | 'documents') => {
  activeTab.value = tab
}

</script>

<template>
  <AdminLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Edit Crew</h6>
      </div>
    </header>

    <Tabs @clickTab="handleClickTab" />

    <div class="flex flex-1 flex-col gap-4 p-4 pt-2">
      <div>
        <EditCrewForm v-if="activeTab === 'profile'" :ranks="ranks" :crew="crew.data" />
      </div>
      <div>
        <DocumentList v-if="activeTab === 'documents'" :documents="crew.crewDocuments" />
      </div>
    </div>
    <Toaster />
  </AdminLayout>
</template>