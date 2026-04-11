<script setup lang="ts">
import Separator from '@/Components/ui/separator/Separator.vue'
import { SidebarTrigger } from '@/Components/ui/sidebar'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import 'vue-sonner/style.css'
import { Toaster } from '@/Components/ui/sonner'
import Card from '@/Components/ui/card/Card.vue'
import EditPersonalInformationForm from './Partials/EditPersonalInformationForm.vue'
import EditPasswordForm from './Partials/EditPasswordForm.vue'
const props = defineProps({
  personalInformation: Object,
  password: Object,
})

</script>

<template>
  <AdminLayout>
    <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
      <div class="flex items-center gap-2 px-4">
        <SidebarTrigger class="-ml-1" />
        <Separator orientation="vertical" class="mr-2 data-[orientation=vertical]:h-4" />
        <h6 class="scroll-m-20 text-xl font-semibold tracking-tight">Profile</h6>
      </div>
    </header>

    <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
      <div>
        <h3 class="text-lg font-semibold">Personal Information</h3>
        <p class="text-sm">Update your personal details and profile information.</p>
        <Card class="p-4 mt-2">
          <EditPersonalInformationForm :data="props.personalInformation.data" />
        </Card>
      </div>
      <div>
        <h3 class="text-lg font-semibold">Security</h3>
        <p class="text-sm">Manage your account security and authentication.</p>
        <Card class="p-4 mt-2">
          <EditPasswordForm :data="props.password.data" />
        </Card>
      </div>
    </div>
    <Toaster />
  </AdminLayout>
</template>