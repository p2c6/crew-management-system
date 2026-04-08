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
import EditUserForm from './Partials/EditUserForm.vue'
import DeleteUserDialog from './Partials/DeleteUserDialog.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { StoreUserForm } from '@/types/User'
const props = defineProps({
  users: Object,
  roles: Object,
  filters: Object,
})

const columns = [
  {
    accessorKey: 'full_name',
    label: 'Full Name',
  },
  {
    accessorKey: 'email',
    label: 'E-mail',
  },
  {
    accessorKey: 'role.name',
    label: 'Role Name',
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
          name: EditUserForm,
          resources: {
            roles: props.roles
          }
        }
      },
      {
        icon: IconPencil,
        label: 'Delete',
        type: 'component',
        component: {
          name: DeleteUserDialog,
        }
      },
    ]
  }
];

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<StoreUserForm>({
  role_id: '',
  full_name: '',
  email: '',
  password: '',
});

const createUser = () => {
  form.post(route('system-administrator.users.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('User has been created')
    },
    onError: (error) => {
      console.log('create user error', error)
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
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Users</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div class="flex justify-end">
        <Dialog v-model:open="isOpen">
          <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
              <IconPlus />
              Add User
            </Button>
          </DialogTrigger>

          <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
              <DialogTitle>Add user</DialogTitle>
              <DialogDescription>
                Add your new user here.
              </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="createUser">
              <div class="grid gap-4 mb-2">
                <div class="grid gap-3">
                  <Label for="full-name">Full Name</Label>
                  <Input type="text" id="full-name" name="full_name" v-model="form.full_name" />
                  
                <InputError
                    :message="form.errors.full_name"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="email">E-mail</Label>
                  <Input type="email" id="email" name="name" v-model="form.email" />
                  
                <InputError
                    :message="form.errors.email"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="slug">Password</Label>
                  <Input type="password" id="slug" name="slug" v-model="form.password" />
                  
                <InputError
                    :message="form.errors.password"
                />
                </div>
                <div class="grid gap-3">
                  <Label for="role">Role</Label>
                  <Select id="role" name="role" v-model="form.role_id">
                    <SelectTrigger>
                      <SelectValue placeholder="Select a role" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="role in props.roles.data" :value="role.id" :key="role.id">
                          {{ role.name }}
                        </SelectItem>
                    </SelectContent>
                  </Select>
                  
                <InputError
                    :message="form.errors.role_id"
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
        :resource="users"
        :columns="columns"
        :filters="filters"
        search-placeholder="Search role..."
      />

    </div>
    <Toaster />
  </AdminLayout>
</template>