<script setup lang="ts">
import { Button } from '@/Components/ui/button'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'
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
} from "@tabler/icons-vue"
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
import { DateFormatter, getLocalTimeZone, today, DateValue, parseDate } from '@internationalized/date'
import { cn } from '@/lib/utils'
import { Calendar } from '@/Components/ui/calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import FileUpload from "@/Components/FileUpload.vue"
import { CalendarIcon } from 'lucide-vue-next'
import { route } from 'ziggy-js'
import { UpdateDocumentForm } from '@/types/Document'

const props = defineProps<{
  data: Object,
  resources: Object
}>()

const defaultPlaceholder = today(getLocalTimeZone())
const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})
const maxDate = today(getLocalTimeZone())

const formatDateValue = (date: DateValue) => {
  return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
}

const isOpen = ref(false)

const handleFormSuccess = () => {
  isOpen.value = false
}
const existingFileRemoved = ref(false)

const existingFiles = props.data?.id
  ? [{
    source: route('staff.crews.documents.view', { document: props.data.id }),
    options: {
      type: 'local',
      file: {
        name: props.data.file_name ?? 'document.pdf',
        size: 0,
      }
    }
  }]
  : []

const form = useForm<UpdateDocumentForm>({
  document_type_id: props.data?.document_type.id,
  code: props.data.code,
  documents: [],
  issued_date: props.data?.issued_date?.original
    ? parseDate(props.data.issued_date.original.slice(0, 10))
    : null,
  expiry_date: props.data?.expiry_date?.original
    ? parseDate(props.data.expiry_date.original.slice(0, 10))
    : null,
})

const updateDocument = () => {
  const newUploads = form.documents.filter(f => f.origin !== 3)
  const fileWasChanged = newUploads.length > 0

  form.transform(data => ({
    ...data,
    documents: fileWasChanged
      ? newUploads.map(f => f.serverId)
      : [],
    existing_document_id: (!fileWasChanged && !existingFileRemoved.value)
      ? props.data.id
      : null,
    remove_existing_document: existingFileRemoved.value && !fileWasChanged,
    issued_date: data.issued_date ? formatDateValue(data.issued_date) : null,
    expiry_date: data.expiry_date ? formatDateValue(data.expiry_date) : null,
  })).put(route('staff.crews.documents.update', props.data.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Document has been updated')
    },
    onError: (error) => {
      console.log('update document error', error)
    }
  })
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <div
        className="flex items-center py-2 px-1 rounded-md w-full text-sm hover:bg-blue-100 hover:text-blue-900 cursor-default">
        <IconPencil class="size-4" />
        <p class="ml-2">
          Edit
        </p>
      </div>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[700px] flex flex-col max-h-[90vh]">
      <DialogHeader class="shrink-0">
        <DialogTitle>Edit Document</DialogTitle>
        <DialogDescription>
          Edit crew documents below.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="updateDocument" class="flex flex-col flex-1 min-h-0">
        <!-- Scrollable entries area -->
        <div class="overflow-y-auto flex-1 pr-1 space-y-4 py-2">
          <div class="border rounded-lg p-4 relative bg-muted/30">

            <div class="grid gap-4">
              <!-- Document Type -->
              <div class="grid gap-2">
                <Label>Document Type</Label>
                <Select v-model="form.document_type_id">
                  <SelectTrigger>
                    <SelectValue placeholder="Select a document type" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem v-for="documentType in props.resources.documentTypes?.data" :value="documentType.id"
                      :key="documentType.id">
                      {{ documentType.name }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <InputError :message="form.errors.document_type_id" />
              </div>

              <!-- File Upload -->
              <div class="grid gap-2">
                <Label>File</Label>
                <FileUpload  
                  v-model="form.documents"
                  :existing-files="existingFiles"
                  v-model:existingFileRemoved="existingFileRemoved" 
                />
                <InputError :message="form.errors.documents" />
              </div>

              <!-- Code -->
              <div class="grid gap-2">
                <Label>Code</Label>
                <Input type="text" v-model="form.code" placeholder="Enter document code" />
                <InputError :message="form.errors.code" />
              </div>

              <!-- Issued Date & Expiry Date side by side -->
              <div class="grid grid-cols-2 gap-3">
                <div class="grid gap-2">
                  <Label>Issued Date</Label>
                  <Popover v-slot="{ close }">
                    <PopoverTrigger as-child>
                      <Button type="button" variant="outline"
                        :class="cn('w-full justify-start text-left font-normal', !form.issued_date && 'text-muted-foreground')">
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ form.issued_date ? df.format(form.issued_date.toDate(getLocalTimeZone())) : "Pick a date" }}
                      </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0" align="start">
                      <Calendar v-model="form.issued_date" :default-placeholder="defaultPlaceholder"
                        layout="month-and-year" :max-value="maxDate" initial-focus @update:model-value="close" />
                    </PopoverContent>
                  </Popover>
                <InputError :message="form.errors.issued_date" />
                </div>

                <div class="grid gap-2">
                  <Label>Expiry Date</Label>
                  <Popover v-slot="{ close }">
                    <PopoverTrigger as-child>
                      <Button type="button" variant="outline"
                        :class="cn('w-full justify-start text-left font-normal', !form.expiry_date && 'text-muted-foreground')">
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ form.expiry_date ? df.format(form.expiry_date.toDate(getLocalTimeZone())) : "Pick a date" }}
                      </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0" align="start">
                      <Calendar v-model="form.expiry_date" :default-placeholder="defaultPlaceholder"
                        layout="month-and-year" initial-focus @update:model-value="close" />
                    </PopoverContent>
                  </Popover>
                  <InputError :message="form.errors.expiry_date" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer stays fixed at bottom -->
        <DialogFooter class="shrink-0 pt-3 border-t mt-2">
          <DialogClose as-child>
            <Button type="button" variant="outline">Cancel</Button>
          </DialogClose>
          <Button type="submit" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : `Save` }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>