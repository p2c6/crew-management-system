<script setup lang="ts">
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
import EditRoleForm from './Partials/EditRoleForm.vue'
import DeleteRoleDialog from './Partials/DeleteRoleDialog.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue'
import { StoreRoleForm } from '@/types/Role'
const props = defineProps({
  roles: Object,
  filters: Object,
})

const columns = [
  {
    accessorKey: 'name',
    label: 'Role Name',
  },
  {
    accessorKey: 'slug',
    label: 'Slug',
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
          name: EditRoleForm
        }
      },
      {
        icon: IconPencil,
        label: 'Delete',
        type: 'component',
        component: {
          name: DeleteRoleDialog
        }
      },
    ]
  }
];

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<StoreRoleForm>({
  name: '',
  slug: '',
});

const createRole = () => {
  form.post(route('system-administrator.roles.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Role has been created')
    },
    onError: (error) => {
      console.log('create role error', error)
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
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Roles</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Dialog v-model:open="isOpen">
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Add Role
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Add role</DialogTitle>
              <DialogDescription>
                Add your new role here.
              </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="createRole">
              <div class="grid gap-4 mb-2">
                <div class="grid gap-3">
                  <Label for="name">Name</Label>
                  <Input id="name" name="name" v-model="form.name" />
                  
                <InputError
                    :message="form.errors.name"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="slug">Slug</Label>
                  <Input id="slug" name="slug" v-model="form.slug" />
                  
                <InputError
                    :message="form.errors.slug"
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
        :resource="roles"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search role..."
      />

    </div>
    <Toaster />
  </AdminLayout>
</template>