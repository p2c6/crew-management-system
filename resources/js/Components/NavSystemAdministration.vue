<script setup lang="ts">
import type { Component } from "vue"
import { Link } from "@inertiajs/vue3"
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/Components/ui/sidebar"

interface Document {
  label: string
  url: string
  icon?: Component
}

defineProps<{
  items: Document[]
}>()
</script>

<template>
  <SidebarGroup class="group-data-[collapsible=icon]:hidden">
    <SidebarGroupLabel>System / Administration</SidebarGroupLabel>
    <SidebarMenu>
      <SidebarMenuItem v-for="item in items" :key="item.label">
        <SidebarMenuButton as-child>
          <Link :href="item.url">
            <component :is="item.icon" />
            <span>{{ item.label }}</span>
          </Link>
        </SidebarMenuButton>
      </SidebarMenuItem>
    </SidebarMenu>
  </SidebarGroup>
</template>
