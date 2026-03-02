<script setup lang="ts">
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
import { DeleteUserForm } from '@/types/User';
import { useForm } from '@inertiajs/vue3';
import { IconTrash } from "@tabler/icons-vue"
import { ref } from 'vue';
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'

const props = defineProps<{
  data: number
}>()

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<DeleteUserForm>({
  id: props.data
});

const deleteUser = () => {
  form.delete(route('system-administrator.users.destroy', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('User has been deleted')
    },
    onError: (error) => {
      console.log('delete user error', error)
    },
    onFinish: () => form.reset(),
  })
}

</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <div class="flex items-center gap-2 cursor-pointer">
        <IconTrash class="size-4" />
        Delete
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Delete user</DialogTitle>
        <DialogDescription>
          Are you sure you want to delete this user?
        </DialogDescription>
      </DialogHeader>

      <form>
        <DialogFooter>
          <DialogClose as-child>
            <Button variant="outline">Cancel</Button>
          </DialogClose>
          <Button type="submit" @click.prevent="deleteUser">Save</Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>