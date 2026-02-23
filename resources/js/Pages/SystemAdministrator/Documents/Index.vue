<script setup>
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import { Button } from '@/Components/ui/button'
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
import {
  IconPencil,
  IconPlus,
  IconTrash,
} from "@tabler/icons-vue"
import EditDocumentForm from './Partials/EditDocumentForm.vue'
import DeleteDocumentDialog from './Partials/DeleteDocumentDialog.vue'
const props = defineProps({
  documents: Object,
  filters: Object,
})

const columns = [
  {
    accessorKey: 'name',
    label: 'Document Name',
  },
  {
    accessorKey: 'action',
    label: 'Action',
    visiblity: 'hidden',
    allowedActions: [
      {
        icon: IconPencil,
        label: 'Edit',
        component: EditDocumentForm
      },
      {
        icon: IconPencil,
        label: 'Delete',
        component: DeleteDocumentDialog
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
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Documents</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Dialog>
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Add Document
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Add document</DialogTitle>
              <DialogDescription>
                Add your new document here.
              </DialogDescription>
            </DialogHeader>

            <form>
              <div class="grid gap-4 mb-2">
                <div class="grid gap-3">
                  <Label for="name-1">Name</Label>
                  <Input id="name-1" name="name" />
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
      </div>
        <DataTable
        :resource="documents"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search documents..."
      />

    </div>
  </AdminLayout>
</template>