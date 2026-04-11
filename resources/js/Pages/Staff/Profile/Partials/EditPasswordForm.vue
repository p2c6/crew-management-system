<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Password, UpdatePasswordForm } from '@/types/Profile'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'

const props = defineProps<{
    data: Password
}>()
const isOpen = ref(false);

const handleFormSuccess = () => {
  isOpen.value = false; 
};

const form = useForm<UpdatePasswordForm>({
  id: props.data.id,
  current_password: '',
  password: '',
  password_confirmation: ''
});

const updateRank = () => {
  form.put(route('staff.profile.update.password', form.id), {
    preserveScroll: true,
    onSuccess: () => {
      handleFormSuccess()
      toast.success('Password has been updated')
    },
    onError: (error) => {
      console.log('Error updating Password', error)
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
            <Label for="current-password">Current Password</Label>
            <Input type="password" id="current-password" name="current_password" v-model="form.current_password" />
            
          <InputError
              :message="form.errors.current_password"
          />
          </div>
          <div class="grid gap-3">
            <Label for="new-password">New Password</Label>
            <Input type="password" id="new-password" name="password" v-model="form.password" />
            
          <InputError
              :message="form.errors.password"
          />
          </div>
          <div class="grid gap-3">
            <Label for="confirm-password">Confirm Password</Label>
            <Input type="password" id="confirm-password" name="password_confirmation" v-model="form.password_confirmation" />
            
          <InputError
              :message="form.errors.password_confirmation"
          />
          </div>
         
        </div>
        <div class="flex justify-end">
            <Button type="submit">Update</Button>
        </div>
      </form>
</template>