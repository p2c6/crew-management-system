<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import CreateCrewForm from './Partials/CreateCrewForm.vue'

defineProps<{
  ranks: Object
}>()


</script>

<template>
  <AdminLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Create Crew</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div>
            <CreateCrewForm :ranks="ranks" />
      </div>
    </div>
    <Toaster />
  </AdminLayout>
</template>