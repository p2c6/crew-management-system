<script setup lang="ts">
import type { Component } from "vue"

import {
  SidebarGroup,
  SidebarGroupContent,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/Components/ui/sidebar"
import { Link } from "@inertiajs/vue3"

interface NavItem {
  title: string
  url: string
  icon?: Component
}

defineProps<{
  items: NavItem[]
}>()
</script>

<template>
  <SidebarGroup>
    <SidebarGroupContent class="flex flex-col gap-2">
      <SidebarMenu>
        <SidebarMenuItem v-for="item in items" :key="item.title">
          <Link :href="item.url">
          <SidebarMenuButton :tooltip="item.title">
              <component :is="item.icon" v-if="item.icon" />
              <span>{{ item.title }}</span>
            </SidebarMenuButton>
          </Link>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarGroupContent>
  </SidebarGroup>
</template>
