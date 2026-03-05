<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { Button } from '@/Components/ui/button'
import {ChevronLeftIcon, ChevronRightIcon, MoreHorizontalIcon } from 'lucide-vue-next'
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
  IconDotsVertical,
} from "@tabler/icons-vue"

import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import SidebarMenuButton from './ui/sidebar/SidebarMenuButton.vue'

interface Meta {
  current_page: number
  from: number
  last_page: number
  path: string
  to: number
  per_page: number
  total: number
}

interface Links {
  first: string
  last: string
  prev: string | null
  next: string | null
}

interface ResourceCollection {
  data: any[]
  links: Links
  meta: Meta
}

interface Component {
  name: string
  resources: any[]
}

interface AllowedActions {
  icon: any;
  label: string;
  type: string;
  url?: (row: any) => string;
  component: Component | null;
}


interface ColumnDef {
  accessorKey: string
  label: string,
  allowedActions: AllowedActions[]
}

interface PageLink {
  page: number
  active: boolean
}

const props = defineProps<{
  resource: ResourceCollection
  columns: ColumnDef[]
  filters?: Record<string, any>
  searchPlaceholder?: string
}>()

const meta = computed(() => props.resource.meta)
const links = computed(() => props.resource.links)

const hasPrev = computed(() => links.value.prev !== null)
const hasNext = computed(() => links.value.next !== null)

const pageLinks = computed<PageLink[]>(() => {
  const pages: PageLink[] = []
  for (let i = 1; i <= meta.value.last_page; i++) {
    pages.push({
      page: i,
      active: i === meta.value.current_page,
    })
  }
  return pages
})

function goToPage(page: number) {
  router.visit(meta.value.path, {
    data: { ...props.filters, page },
    preserveScroll: true,
    preserveState: true,
  })
}

function showEllipsisBefore(index: number): boolean {
  if (index === 0) return false
  return pageLinks.value[index].page - pageLinks.value[index - 1].page > 1
}

function getNestedValue(obj:any, path:any):any {
  return path.split('.').reduce((acc:any, key:any) => acc?.[key], obj);
}
</script>

<template>
  <div class="flex flex-col gap-4 rounded-lg border p-2">
    <Table>
      <TableHeader class="bg-muted sticky top-0 z-10">
        <TableRow>
          <TableHead  class="py-1 px-2 text-xs" v-for="column in columns" :key="column.accessorKey">
            <span v-if="column.label !== 'Action'">{{ column.label }}</span>
          </TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="row in resource.data" :key="row.id"  class="h-8">
          <TableCell
            v-for="column in columns"
            :key="column.accessorKey"
            class="py-3 px-3 text-md font-medium"
          >
          <span v-if="column.accessorKey !== 'action'">
            {{ getNestedValue(row, column.accessorKey) }}
          </span>
          <span v-show="column.accessorKey === 'action'">
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <SidebarMenuButton
                  size="lg"
                  className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                >
                  <IconDotsVertical class="ml-auto size-4" />
                </SidebarMenuButton>
              </DropdownMenuTrigger>
              <DropdownMenuContent
                className="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                :side-offset="4"  
                align="end"
              >
              <template v-for="(action, index) in column.allowedActions" :key="index">
                
                <DropdownMenuSeparator v-if="index !== 0" />
                <DropdownMenuItem as-child>
                  <Link v-if="action.type == 'link'" :href="action?.url?.(row)">
                    <component :is="action.icon" :data="row" />
                    {{ action.label }}
                  </Link>
              
                </DropdownMenuItem>
                <DropdownMenuItem
                  v-if="action.type === 'component'"
                  as-child
                >
                  <component
                    :is="action?.component?.name"
                    :data="row.id"
                  />
                </DropdownMenuItem>
              </template>
              
              </DropdownMenuContent>
            </DropdownMenu>
          </span>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <div className="flex items-center justify-between px-2">
      <p className="text-sm text-muted-foreground">
        Showing {{ meta.from }}–{{ meta.to }} of {{ meta.total }} results
      </p>

      <nav className="flex flex-row items-center gap-1">
        <Button
          variant="ghost"
          size="default"
          class="gap-1 px-2.5 sm:pr-2.5"
          :disabled="!hasPrev"
          :class="{ 'pointer-events-none opacity-50': !hasPrev }"
          @click="goToPage(meta.current_page - 1)"
        >
          <ChevronLeftIcon className="size-4" />
          <span className="hidden sm:block">Previous</span>
        </Button>

        <template v-for="(link, index) in pageLinks" :key="link.page">
          <span
            v-if="showEllipsisBefore(index)"
            className="flex size-9 items-center justify-center text-muted-foreground"
          >
            <MoreHorizontalIcon className="size-4" />
            <span className="sr-only">More pages</span>
          </span>

          <Button
            :variant="link.active ? 'default' : 'ghost'"
            size="icon"
            class="size-9"
            @click="goToPage(link.page)"
          >
            {{ link.page }}
          </Button>
        </template>

        <Button
          variant="ghost"
          size="default"
          class="gap-1 px-2.5 sm:pl-2.5"
          :disabled="!hasNext"
          :class="{ 'pointer-events-none opacity-50': !hasNext }"
          @click="goToPage(meta.current_page + 1)"
        >
          <span class="hidden sm:block">Next</span>
          <ChevronRightIcon class="size-4" />
        </Button>
      </nav>
    </div>
  </div>
</template>