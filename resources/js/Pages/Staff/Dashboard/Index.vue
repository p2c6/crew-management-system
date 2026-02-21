<script setup>
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList } from '@/Components/ui/breadcrumb';
import Separator from '@/Components/ui/separator/Separator.vue';
import { SidebarInset, SidebarTrigger } from '@/Components/ui/sidebar';
import StaffLayout from '@/Layouts/StaffLayout.vue';
import { ChevronRight } from 'lucide-vue-next';
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
                    Building Your Application
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <ChevronRight size="1rem" />
                <BreadcrumbSeparator class="hidden md:block" />
                <BreadcrumbItem>
                    <BreadcrumbPage>Data Fetching</BreadcrumbPage>
                </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
            </div>
        </header>
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            
        </div>
    </StaffLayout>
</template>
