<script setup lang="ts">
import { Button } from '@/Components/ui/button'
import InputError from '@/Components/InputError.vue'
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { useForm } from '@inertiajs/vue3'
import { IconPencil } from "@tabler/icons-vue"
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import { EditUserForm, User } from '@/types/User'

const props = defineProps<{
  data: User,
  resources: any
}>()

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<EditUserForm>({
  id: props.data.id,
  full_name: props.data.full_name,
  role_id: props.data.role.id,
  email: props.data.email,
  password: props.data.password,
});

const updateUser = () => {
  form.put(route('system-administrator.users.update', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('User has been updated')
    },
    onError: (error) => {
      console.log('error updating user', error)
    },
    onFinish: () => {
      form.reset()
    }
  });
}

</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <div className="flex items-center py-2 px-1 rounded-md w-full text-sm hover:bg-blue-100 hover:text-blue-900 cursor-pointer">
        <IconPencil class="size-4" />
        <p class="ml-2">
          Edit
        </p>
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Edit document</DialogTitle>
        <DialogDescription>
          Update document details here.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="updateUser">
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
                <SelectItem v-for="role in props.resources.roles.data" :value="role.id" :key="role.id">
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
</template>