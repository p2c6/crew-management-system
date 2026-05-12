<script setup lang="ts">
import { Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList } from '@/Components/ui/breadcrumb';
import Separator from '@/Components/ui/separator/Separator.vue';
import { SidebarTrigger } from '@/Components/ui/sidebar';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
    documents: String,
    ranks: String,
    document_types: String,
    roles: String,
    users: String,
}>()

</script>

<template>
    <AdminLayout>
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
        <div class="grid gap-4 p-4 pt-0 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6">
            <Card class="@container/card h-full">
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
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Ranks</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.ranks}}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Includes all available crew positions
                    </div>
                </CardFooter>
            </Card>
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Document Types</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.document_types}}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Different categories of documents (e.g., Passport, Seaman’s Book, Certificates)
                    </div>
                </CardFooter>
            </Card>
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Roles</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.roles}}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Defines system access levels and permissions for each user role
                    </div>
                </CardFooter>
            </Card>
            <Card class="@container/card">
                <CardHeader>
                    <CardDescription>Total Users</CardDescription>
                    <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {{ props.users}}
                    </CardTitle>
                </CardHeader>
                <CardFooter class="flex-col items-start gap-1.5 text-sm">
                    <div class="text-muted-foreground">
                        Total registered users including admins, staff, and crew accounts
                    </div>
                </CardFooter>
            </Card>
        </div>
    </AdminLayout>
</template>
