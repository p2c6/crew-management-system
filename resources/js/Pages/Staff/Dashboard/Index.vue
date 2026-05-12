<script setup lang="ts">
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList } from '@/Components/ui/breadcrumb';
import Separator from '@/Components/ui/separator/Separator.vue';
import { SidebarTrigger } from '@/Components/ui/sidebar';
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { ChevronRight } from 'lucide-vue-next';
import {
  Card,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/Components/ui/card"

const props = defineProps<{
    crews: String,
    documents: String
}>()

</script>

<template>
    <StaffLayout>
        <header class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12">
            <div class="flex items-center gap-2 px-4">
            <SidebarTrigger class="-ml-1" />
            <Separator
                orientation="vertical"
                class="mr-2 data-[orientation=vertical]:h-4"
            />
            <Breadcrumb>
                <BreadcrumbList>
                <BreadcrumbItem class="hidden md:block">
                    <BreadcrumbLink href="#">
                    Dashboard
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <ChevronRight size="1rem" />
                <BreadcrumbSeparator class="hidden md:block" />
                <BreadcrumbItem>
                    <BreadcrumbPage>Home</BreadcrumbPage>
                </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
            </div>
        </header>
        <div class="flex flex-1 flex-row gap-4 p-4 pt-0">
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Crews</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.crews }}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Includes all active and onboard crew members
                    </div>
                </CardFooter>
            </Card>
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Documents</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.documents}}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Total number of documents uploaded for all crew members
                    </div>
                </CardFooter>
            </Card>
        </div>
    </StaffLayout>
</template>
