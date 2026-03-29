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
import { DeleteRankForm } from '@/types/Rank';
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

const form = useForm<DeleteRankForm>({
  id: props.data
});

const deleteRank = () => {
  form.delete(route('system-administrator.ranks.destroy', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('Rank has been deleted')
    },
    onError: (error) => {
      console.log('delete rank error', error)
    },
    onFinish: () => form.reset(),
  })
}

</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <div className="flex items-center py-2 px-1 rounded-md w-full text-sm hover:bg-blue-100 hover:text-blue-900 cursor-pointer">
        <IconTrash class="h-4 w-4" />
        <p class="ml-2">
          Delete
        </p>
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Delete rank</DialogTitle>
        <DialogDescription>
          Are you sure you want to delete this rank?
        </DialogDescription>
      </DialogHeader>

      <form>
        <DialogFooter>
          <DialogClose as-child>
            <Button variant="outline">Cancel</Button>
          </DialogClose>
          <Button type="submit" @click.prevent="deleteRank">Save</Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>