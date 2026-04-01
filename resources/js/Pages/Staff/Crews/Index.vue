<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import { Button } from '@/Components/ui/button'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import { Link } from "@inertiajs/vue3"
import {
  IconPencil,
  IconPlus,
} from "@tabler/icons-vue"
import DeleteCrewDialog from './Partials/DeleteCrewDialog.vue'
import { route } from 'ziggy-js'
import { Trash } from 'lucide-vue-next'
import StaffLayout from '@/Layouts/StaffLayout.vue'
</script>

<template>
  <StaffLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Crews</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Link :href="route('system-administrator.crews.create')">
          <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
            <IconPlus />
            Add Crew
          </Button>
        </Link>
      </div>
    </div>
    <Toaster />
  </StaffLayout>
</template>