<script setup lang="ts">
import AppSidebar from "@/Components/AppSidebar.vue"
import {
  SidebarProvider,
} from "@/Components/ui/sidebar"
</script>

<template>
  <SidebarProvider>
    <AppSidebar />
    <SidebarInset>
      <slot />
    </SidebarInset>
  </SidebarProvider>
</template>
