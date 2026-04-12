<script setup lang="ts">
import { route } from "ziggy-js"
import {
  IconClipboardTypography,
  IconDashboard,
  IconDatabase,
  IconInnerShadowTop,
  IconJewishStar,
  IconListDetails,
  IconReport,
  IconUserCog,
  IconUserHexagon,
  IconUsers,
  IconUsersGroup,
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
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue"
const page = usePage();

const iconMap: Record<string, any> = {
  dashboard: IconDashboard,
  crews: IconUserHexagon,
  ranks: IconJewishStar,
  documentTypes: IconClipboardTypography,
  roles: IconUserCog,
  users: IconUsersGroup,
};

const pluck = (resource: any[], key: string) => {
  return resource.map(element => element[key]);
}

const appendUrlAndIcon = (entity: any[]) => {
  return entity.map(element => {
        const dd = {
          ...element,
          url: route(`${auth.value.role.slug.replace("_", "-")}.${element.label.toLowerCase().replace(" ", "-")}.index`),
          icon: iconMap[element.icon] || null,
        }

        return dd
  });
}

const auth = computed(() => page.props.auth.user);

const main = computed(() => {
  const accessModules = page.props.auth.user.accessModules;

  const entity = pluck(accessModules, 'entity')
  const filteredEntity = entity.filter(element => element.is_main == 1)
  const withUrl = appendUrlAndIcon(filteredEntity)

  return withUrl;
});

const system = computed(() => {
  const accessModules = page.props.auth.user.accessModules;

  const entity = pluck(accessModules, 'entity')
  const filteredEntity = entity.filter(element => element.is_main == 0)
  const withUrl = appendUrlAndIcon(filteredEntity)

  return withUrl;
});

const data = {
  user: {
    ...auth.value,
    avatar: "https://ui-avatars.com/api/?name=Admin",
  },
  main: main.value,
  system: system.value
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
               <img
            src="../../../public/images/logo_256.png"
            alt="Logo"
            class="h-4 w-auto md:h-8 lg:h-12 object-contain"
          >
              <span class="text-base font-semibold">SeaLink Crewing Inc.</span>
            </a>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>
    <SidebarContent>
      <NavMain :items="data.main" />
      <NavSystemAdministration :items="data.system" v-show="data.system.length > 0" />
    </SidebarContent>
    <SidebarFooter>
      <NavUser :user="data.user" />
    </SidebarFooter>
  </Sidebar>
</template>
