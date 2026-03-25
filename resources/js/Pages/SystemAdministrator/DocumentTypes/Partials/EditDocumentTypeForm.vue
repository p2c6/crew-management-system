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
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import { DocumentType, UpdateDocumentTypeForm } from '@/types/DocumentType'
import 'vue-sonner/style.css'

const props = defineProps<{
  data: DocumentType
}>()

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<UpdateDocumentTypeForm>({
  id: props.data.id,
  name: props.data.name,
});

const updateDocument = () => {
  form.put(route('system-administrator.document-types.update', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('Document Type has been updated')
    },
    onError: (error) => {
      console.log('Update Document Type Error', error)
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
      <div className="flex items-center py-2 px-1 rounded-md w-full text-sm hover:bg-blue-100 hover:text-blue-900 cursor-default">
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

      <form @submit.prevent="updateDocument">
        <div class="grid gap-4 mb-2">
          <div class="grid gap-3">
            <Label for="name">Name</Label>
            <Input id="name" name="name" v-model="form.name" />
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
</template>