<script setup lang="ts">
import { route } from "ziggy-js"
import {
  IconDashboard,
  IconDatabase,
  IconInnerShadowTop,
  IconListDetails,
  IconReport,
  IconUsers,
} from "@tabler/icons-vue"

import NavMain from "@/Components/NavMain.vue"
import NavUser from "@/Components/NavUser.vue"
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/Components/ui/sidebar"
import NavSystemAdministration from "./NavSystemAdministration.vue"

const data = {
  user: {
    name: "shadcn",
    email: "m@example.com",
    avatar: "https://ui-avatars.com/api/?name=Admin",
  },
  navMain: [
    {
      title: "Dashboard",
      url: "/",
      icon: IconDashboard,
    },
    {
      title: "Crews",
      url: route('system-administrator.crews.index'),
      icon: IconListDetails,
    },
  ],
  system_administration: [
    {
      name: "Ranks",
      url: route('system-administrator.ranks.index'),
      icon: IconDatabase,
    },
    {
      name: "Document Types",
      url: route('system-administrator.document-types.index'),
      icon: IconReport,
    },
    {
      name: "Roles",
      url: route('system-administrator.roles.index'),
      icon: IconReport,
    },
    {
      name: "Users",
      url: route('system-administrator.users.index'),
      icon: IconUsers,
    },
  ],
}
</script>

<template>
  <Sidebar collapsible="offcanvas">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton
            as-child
            class="data-[slot=sidebar-menu-button]:!p-1.5"
          >
            <a href="#">
              <IconInnerShadowTop class="!size-5" />
              <span class="text-base font-semibold">SeaLink Crewing Co.</span>
            </a>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>
    <SidebarContent>
      <NavMain :items="data.navMain" />
      <NavSystemAdministration :items="data.system_administration" />
    </SidebarContent>
    <SidebarFooter>
      <NavUser :user="data.user" />
    </SidebarFooter>
  </Sidebar>
</template>
