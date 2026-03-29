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
import { UpdateRankForm } from '@/types/Rank'
import { useForm } from '@inertiajs/vue3'
import { IconPencil } from "@tabler/icons-vue"
import { onMounted, ref } from 'vue'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'

interface Rank {
  id: number,
  code: string,
  short_name: string
  alias: string
}
const props = defineProps<{
  data: Rank
}>()

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<UpdateRankForm>({
  id: props.data.id,
  code: props.data.code,
  short_name: props.data.short_name,
  alias: props.data.alias,
});

const updateRank = () => {
  form.put(route('system-administrator.ranks.update', form.id), {
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
        <DialogTitle>Edit rank</DialogTitle>
        <DialogDescription>
          Update rank details here.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="updateRank">
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
</template>