<script setup lang="ts">
import { Toaster } from'@/Components/ui/sonner'
import 'vue-sonner/style.css'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { useForm } from '@inertiajs/vue3'
import { Ref, ref } from 'vue'
import InputError from '@/Components/InputError.vue'
import { toast } from 'vue-sonner'
import type { DateValue } from '@internationalized/date'
import { DateFormatter, getLocalTimeZone, today } from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Calendar } from '@/Components/ui/calendar'
import { Badge } from '@/Components/ui/badge'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { StoreCrewForm } from '@/types/Crew'
import { Button } from '@/Components/ui/button'

const props = defineProps<{
  ranks: Object
}>()

const form = useForm<StoreCrewForm>({
  rank_id: '',
  first_name: '',
  middle_name: '',
  last_name: '',
  address: '',
  birth_date: '',
  email: '',
  weight: '',
  height: '',
});

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};
const defaultPlaceholder = today(getLocalTimeZone())
const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})
const maxDate = today(getLocalTimeZone())

const formatDateValue = (date: DateValue) => {
  return `${date.year}-${String(date.month).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
}

const createCrew = () => {
    form.transform((data) => ({
    ...data,
    birth_date: data.birth_date ? formatDateValue(data.birth_date) : null,
  })).post(route('staff.crews.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      handleFormSuccess()
      toast.success('Crew has been created')
    },
    onError: (error) => {
      console.log('Create crew error', error)
    }
  })
}
</script>
<template>
            <form @submit.prevent="createCrew">
              <div class="grid grid-rows-7 gap-5 mb-2">
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <Label for="first-name">First Name</Label>
                        <Input type="text" id="first-name" name="first_name" v-model="form.first_name" />
                        
                        <InputError
                            :message="form.errors.first_name"
                        />
                    </div>
                    <div>
                        <Label for="middle-name">Middle Name</Label>
                        <Input type="text" id="middle-name" name="middle_name" v-model="form.middle_name" />
                        
                        <InputError
                            :message="form.errors.middle_name"
                        />
                    </div>
                    <div>
                        <Label for="last-name">Last Name</Label>
                        <Input type="text" id="last-name" name="middle_name" v-model="form.last_name" />
                        
                        <InputError
                            :message="form.errors.last_name"
                        />
                    </div>
                </div>
                <div class="grid gap-3">
                    <div>
                        <Label for="last-name">Address</Label>
                        <Input type="text" id="address" name="address" v-model="form.address" />
                
                        <InputError
                            :message="form.errors.address"
                        />
                    </div>
                </div>
                <div class="grid gap-3">
                    <Label for="birth-date">Birthday</Label>
                    <div c>

                    <Popover v-slot="{ close }">
                        <PopoverTrigger as-child>
                        <Button
                            variant="outline"
                            :class="cn('w-full justify-start text-left font-normal', !form.birth_date && 'text-muted-foreground')"
                        >
                            <CalendarIcon />
                            {{ form.birth_date ? df.format(form.birth_date.toDate(getLocalTimeZone())) : "Pick a date" }}
                        </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0" align="start">
                        <Calendar
                            v-model="form.birth_date"
                            :default-placeholder="defaultPlaceholder"
                            layout="month-and-year"
                            :max-value="maxDate"
                            initial-focus
                            @update:model-value="close"
                            id="birth-date"
                        />
                        </PopoverContent>
                    </Popover>
                    </div>

                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <Label for="height">
                            Height  
                            <Badge
                            variant="outline"
                            class="bg-blue-500 text-white dark:bg-blue-600"
                          >
                            cm
                          </Badge>
                        </Label>
                        <Input class="mt-2" type="number" id="email" name="email" v-model="form.height" />
                
                        <InputError
                            :message="form.errors.height"
                        />
                    </div>
                    <div>
                          <Label for="weight">
                            Weight  
                            <Badge
                            variant="outline"
                            class="bg-blue-500 text-white dark:bg-blue-600"
                          >
                            kg
                          </Badge>
                        </Label>
                        <Input class="mt-2" type="number" id="weight" name="weight" v-model="form.weight" />
                
                        <InputError
                            :message="form.errors.weight"
                        />
                    </div>
                </div>
                <div class="grid gap-3">
                    <div>
                        <Label for="email">E-mail</Label>
                        <Input type="email" id="email" name="email" v-model="form.email" />
                
                        <InputError
                            :message="form.errors.email"
                        />
                    </div>
                </div>
                <div class="grid gap-3">
                  <Label for="role">Rank</Label>
                  <Select id="role" name="role" v-model="form.rank_id">
                    <SelectTrigger>
                      <SelectValue placeholder="Select a role" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="rank in props.ranks.data" :value="rank.id" :key="rank.id">
                          {{ rank.short_name }}
                        </SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError
                    :message="form.errors.rank_id"
                   />
                </div>
              </div>
              <div>
                <Button type="submit">Save</Button>
              </div>

            </form>
    <Toaster />
</template>