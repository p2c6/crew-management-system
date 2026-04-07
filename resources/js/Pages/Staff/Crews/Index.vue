<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/DataTable.vue'
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
const props = defineProps({
  crews: Object,
  filters: Object,
})

const columns = [
  {
    accessorKey: 'first_name',
    label: 'First Name',
  },
  {
    accessorKey: 'middle_name',
    label: 'Middle Name',
  },
  {
    accessorKey: 'last_name',
    label: 'Last Name',
  },
  {
    accessorKey: 'address',
    label: 'Address',
  },
  {
    accessorKey: 'birth_date',
    label: 'Birth Date',
  },
  {
    accessorKey: 'age',
    label: 'Age',
  },
  {
    accessorKey: 'bmi',
    label: 'BMI',
  },
  {
    accessorKey: 'email',
    label: 'E-mail',
  },
  {
    accessorKey: 'rank.short_name',
    label: 'Rank',
  },
  {
    accessorKey: 'action',
    label: 'Action',
    visiblity: 'hidden',
    allowedActions: [
      {
        icon: IconPencil,
        label: 'Edit',
        type: 'link',
        url: (row: any) => route('staff.crews.edit', row.id),
      },
      {
        icon: Trash,
        label: 'Delete',
        type: 'component',
        component: {
          name: DeleteCrewDialog,
        }
      },
    ]
  }
];
</script>

<template>
  <AdminLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Crews</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Link :href="route('staff.crews.create')">
          <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
            <IconPlus />
            Add Crew
          </Button>
        </Link>
      </div>
        <DataTable
        :resource="crews"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search role..."
      />

    </div>
    <Toaster />
  </AdminLayout>
</template>