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
import EditRankForm from './Partials/EditRankForm.vue'
import DeleteRankDialog from './Partials/DeleteRankDialog.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue'
const props = defineProps({
  ranks: Object,
  filters: Object,
})

const columns = [
  {
    accessorKey: 'code',
    label: 'Code',
  },
  {
    accessorKey: 'short_name',
    label: 'Short Name',
  },
  {
    accessorKey: 'alias',
    label: 'Alias',
  },
  {
    accessorKey: 'action',
    label: 'Action',
    visiblity: 'hidden',
    allowedActions: [
      {
        icon: IconPencil,
        label: 'Edit',
        component: EditRankForm
      },
      {
        icon: IconPencil,
        label: 'Delete',
        component: DeleteRankDialog
      },
    ]
  }
];

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm({
  code: '',
  short_name: '',
  alias: '',
});

const createRank = () => {
  form.post(route('system-administrator.ranks.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Rank has been created')
    },
    onError: (error) => {
      console.log('create rank error', error)
    }
  })
}

</script>

<template>
  <AdminLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Ranks</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Dialog v-model:open="isOpen">
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Add Rank
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Add role</DialogTitle>
              <DialogDescription>
                Add your new rank here.
              </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="createRank">
              <div class="grid gap-4 mb-2">
                <div class="grid gap-3">
                  <Label for="code">Code</Label>
                  <Input id="code" name="code" v-model="form.code" />
                  
                <InputError
                    :message="form.errors.code"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="short-name">Short Name</Label>
                  <Input id="short-name" name="short_name" v-model="form.short_name" />
                  
                <InputError
                    :message="form.errors.short_name"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="alias">Alias</Label>
                  <Input id="alias" name="alias" v-model="form.alias" />
                  
                <InputError
                    :message="form.errors.alias"
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
        :resource="ranks"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search role..."
      />

    </div>
    <Toaster />
  </AdminLayout>
</template>