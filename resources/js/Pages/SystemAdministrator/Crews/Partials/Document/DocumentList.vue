<script setup lang="ts">
import DataTable from '@/Components/DataTable.vue'
import { Button } from '@/Components/ui/button'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import { h } from 'vue'
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
import { DateFormatter, getLocalTimeZone, today, DateValue } from '@internationalized/date'
import { cn } from '@/lib/utils'
import { Calendar } from '@/Components/ui/calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import FileUpload from "@/Components/FileUpload.vue"
import { CalendarIcon } from 'lucide-vue-next'
import EditDocumentForm from './Partials/EditDocumentForm.vue'
import DeleteDocumentDialog from './Partials/DeleteDocumentDialog.vue'
import { route } from 'ziggy-js'
import { StoreDocumentForm } from '@/types/Document'
import dayjs from "dayjs";

const props = defineProps<{
  documents: Object,
  filters: Object,
  documentTypes: Object,
  crewId: number | string,
}>()

const defaultPlaceholder = today(getLocalTimeZone())
const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})
const maxDate = today(getLocalTimeZone())

const formatDateValue = (date: DateValue) => {
  return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
}

const handleExpirationSchemeBG = (date:string): string|undefined => {
  const now = dayjs(); 
  const expirationDate = dayjs(date);

  const daysLeft = Math.ceil(expirationDate.diff(now, 'day', true));

  if (daysLeft < 0) {
    return 'bg-red-700 text-white p-2 rounded-sm';
  }

  if (daysLeft <= 7) {
    return 'bg-red-500 text-white p-2 rounded-sm';
  }

  if (daysLeft <= 30) {
    return 'bg-yellow-300 text-white p-2 rounded-sm';
  }

  if (daysLeft <= 90) {
    return 'bg-orange-300 text-white p-2 rounded-sm';
  }

  return undefined;
}

const columns = [
  {
    accessorKey: 'file_name',
    label: 'File',
    render: (value: string, row: any) =>
      h('a', {
        href: route('system-administrator.crews.documents.view', {
          crew: row.crew_id,
          document: row.id
        }),
        target: '_blank',
        class: 'text-blue-600 hover:underline font-medium',
      }, value)
  },
  {
    accessorKey: 'code',
    label: 'Code',
  },
  {
    accessorKey: 'issued_date.for_human',
    label: 'Issued Date',
  },
  {
    accessorKey: 'expiry_date.for_human',
    label: 'Expiration Date',
    render: (value: string, row: any) => {
      const coloredValue = handleExpirationSchemeBG(row.expiry_date.original)
      return  h('span', {
        class: coloredValue,
      }, value)
    }
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
          name: EditDocumentForm,
          resources: {
            documentTypes: props?.documentTypes
          }
        }
      },
      {
        icon: IconPencil,
        label: 'Delete',
        type: 'component',
        component: {
          name: DeleteDocumentDialog,
        }
      },
    ]
  },
]

const isOpen = ref(false)

const handleFormSuccess = () => {
  isOpen.value = false
}

const form = useForm<StoreDocumentForm>({
  crew_id: props.crewId,
  document_type_id: '',
  code: '',
  documents: [],
  issued_date: '',
  expiry_date: ''
})

const createDocuments = () => {
  form.transform((data) => ({
    ...data,
    documents: data.documents.map((file: any) => file.serverId).filter(Boolean),
    issued_date: data.issued_date ? formatDateValue(data.issued_date) : null,
    expiry_date: data.expiry_date ? formatDateValue(data.expiry_date) : null,
  })).post(route('system-administrator.crews.documents.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Documenthave been added')
    },
    onError: (error) => console.log('create document error', error)
  })
}
</script>

<template>
  <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
    <div class="flex justify-end">
      <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
          <Button variant="outline" size="sm" class="hover:bg-primary hover:text-white">
            <IconPlus />
            Add Document
          </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[700px] flex flex-col max-h-[90vh]">
          <DialogHeader class="shrink-0">
            <DialogTitle>Add Document</DialogTitle>
            <DialogDescription>
              Add one or more crew documents below.
            </DialogDescription>
          </DialogHeader>

          <form @submit.prevent="createDocuments" class="flex flex-col flex-1 min-h-0">
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
                        <SelectItem v-for="documentType in props.documentTypes?.data" :value="documentType.id"
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
                    <FileUpload v-model="form.documents" />
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
                            {{ form.issued_date ? df.format(form.issued_date.toDate(getLocalTimeZone())) : "Pick a date"
                            }}
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
                            {{ form.expiry_date ? df.format(form.expiry_date.toDate(getLocalTimeZone())) : "Pick a date"
                            }}
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
    </div>

    <DataTable :resource="documents" :columns="columns" :filters="filters" search-placeholder="Search documents..." />
  </div>

  <Toaster />
</template>