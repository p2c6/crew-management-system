<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
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
import { useForm } from '@inertiajs/vue3'
import { IconPencil } from "@tabler/icons-vue"
import { onMounted, ref } from 'vue'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'

interface Role {
  id: number,
  name: string,
  slug: string
}
const props = defineProps<{
  data: Role
}>()

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

interface RoleForm {
  id: number,
  name: string,
  slug: string
}

const form = useForm<RoleForm>({
  id: props.data.id,
  name: props.data.name,
  slug: props.data.slug,
});

const updateDocument = () => {
  form.put(route('system-administrator.roles.update', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('Document has been updated')
    },
    onError: (error) => {
      console.log('error updating document', error)
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
      <div class="flex items-center gap-2 cursor-pointer">
        <IconPencil class="size-4" />
        Edit
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Edit document</DialogTitle>
        <DialogDescription>
          Update document details here.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="updateDocument">
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
</template>