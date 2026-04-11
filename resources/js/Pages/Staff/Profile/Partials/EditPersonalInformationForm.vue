<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { PersonalInformation, UpdatePersonalInfoForm } from '@/types/Profile'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'

const props = defineProps<{
    data: PersonalInformation
}>();

const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<UpdatePersonalInfoForm>({
  id: props.data.id,
  first_name: props.data.first_name,
  last_name: props.data.last_name
});

const updateRank = () => {
  form.put(route('staff.profile.update.personal-information', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('Personal Information has been updated')
    },
    onError: (error) => {
      console.log('Error updating Personal Information', error)
    },
    onFinish: () => {
      form.reset()
    }
  });
}

</script>

<template>
      <form @submit.prevent="updateRank">
        <div class="grid gap-4 mb-2">
          <div class="grid gap-3">
            <Label for="code">First Name</Label>
            <Input id="code" name="code" v-model="form.first_name" />
            
          <InputError
              :message="form.errors.first_name"
          />
          </div>
          <div class="grid gap-3">
            <Label for="short-name">Last Name</Label>
            <Input id="short-name" name="short_name" v-model="form.last_name" />
            
          <InputError
              :message="form.errors.last_name"
          />
          </div>
         
        </div>
        <div class="flex justify-end">
            <Button type="submit">Update</Button>
        </div>
      </form>
</template>