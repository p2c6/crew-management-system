<script setup>
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import { Button } from '@/Components/ui/button'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
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
} from "@tabler/icons-vue"
import EditDocumentTypeForm from './Partials/EditDocumentTypeForm.vue'
import DeleteDocumentTypeDialog from './Partials/DeleteDocumentTypeDialog.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue'
const props = defineProps({
  document_types: Object,
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
        type: 'component',
        component: {
          name: EditDocumentTypeForm,
        }
      },
      {
        icon: IconPencil,
        label: 'Delete',
        type: 'component',
        component: {
          name: DeleteDocumentTypeDialog,
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
  name: ''
});

const createeDocumentType = () => {
  form.post(route('system-administrator.document-types.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Document Type has been created')
    },
    onError: (error) => {
      console.log('Create Document Type error', error)
    }
  })
}

</script>

<template>
  <AdminLayout>
    <header className="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div className="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 className="scroll-m-20 text-xl font-semibold tracking-tight">Document Type</h6>
      </div>
    </header>

    <div className="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div className="flex justify-end">
        <Dialog v-model:open="isOpen">
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Add Document Type
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Add document type</DialogTitle>
              <DialogDescription>
                Add your new document type here.
              </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="createeDocumentType">
              <div className="grid gap-4 mb-2">
                <div className="grid gap-3">
                  <Label for="name-1">Name</Label>
                  <Input id="name-1" name="name" v-model="form.name" />
                  
                <InputError
                    :message="form.errors.name"
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
      </div>
        <DataTable
        :resource="document_types"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search document types..."
      />

    </div>
    <Toaster />
  </AdminLayout>
</template>