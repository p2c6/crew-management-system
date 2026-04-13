<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import { Button } from '@/Components/ui/button'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import { Link, useForm } from "@inertiajs/vue3"
import {
  IconPencil,
  IconPlus,
} from "@tabler/icons-vue"
import DeleteCrewDialog from './Partials/DeleteCrewDialog.vue'
import { route } from 'ziggy-js'
import { Trash } from 'lucide-vue-next'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/Components/ui/dialog'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import InputError from '@/Components/InputError.vue'
import { toast } from 'vue-sonner'
import { ref } from 'vue'

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
        url: (row: any) => route('system-administrator.crews.edit', row.id),
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

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm({
  file: ''
});

const importCrew = () => {
  form.post(route('system-administrator.crews.bulk-upload'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Crew has been imported')
    },
    onError: (error) => {
      console.log('Import crew error', error)
    }
  })
}

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  form.file = target.files ? target.files[0] : null
}
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
        <Dialog v-model:open="isOpen">
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Import Crews
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Import Crews</DialogTitle>
              <DialogDescription>
                Import crews here.
              </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="importCrew">
              <div class="grid gap-4 mb-2">
                <div class="grid gap-3">
                  <Label for="name-1">File</Label>
                <Input
                    type="file"
                    @change="handleFileChange"
                  />
                  
                <InputError
                    :message="form.errors.file"
                />
                </div>
              </div>

              <DialogFooter>
                <DialogClose as-child>
                  <Button variant="outline">Cancel</Button>
                </DialogClose>
                <Button type="submit">Save</Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
        <Link :href="route('system-administrator.crews.create')">
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