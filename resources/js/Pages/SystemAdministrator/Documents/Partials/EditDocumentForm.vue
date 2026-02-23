<script setup>
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
import { IconPencil } from "@tabler/icons-vue"
</script>

<template>
  <Dialog>
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

      <form>
        <div class="grid gap-4 mb-2">
          <div class="grid gap-3">
            <Label for="name">Name</Label>
            <Input id="name" name="name" />
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