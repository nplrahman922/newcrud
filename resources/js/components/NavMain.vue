<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import {
    SidebarMenu,
    SidebarMenuItem,
} from '@/components/ui/sidebar'
import { BarChart, Users, Video, Settings, LifeBuoy } from 'lucide-vue-next'

const page = usePage()
const user = computed(() => page.props.auth.user)

const isAdmin = computed(() => user.value?.role === 'admin')
</script>

<template>
    <SidebarMenu>
        <!-- ====== MENU UNTUK SEMUA USER ====== -->
        <SidebarMenuItem :href="route('dashboard')" :active="route().current('dashboard')">
            <template #icon>
                <BarChart :size="16" />
            </template>
            Dashboard
        </SidebarMenuItem>

        <!-- ====== MENU KHUSUS ADMIN ====== -->
        <template v-if="isAdmin">
            <SidebarMenuItem :href="route('admin.users.index')" :active="route().current('admin.users.*')">
                <template #icon>
                    <Users :size="16" />
                </template>
                Manajemen Pengguna
            </SidebarMenuItem>
            <SidebarMenuItem href="#" :active="false"> <!-- Arahkan ke route('admin.courses.index') nanti -->
                <template #icon>
                    <Video :size="16" />
                </template>
                Manajemen Kursus
            </SidebarMenuItem>
        </template>

        <!-- ====== MENU PENGATURAN & BANTUAN ====== -->
        <SidebarMenuItem :href="route('settings.profile')" :active="route().current('settings.*')">
            <template #icon>
                <Settings :size="16" />
            </template>
            Pengaturan
        </SidebarMenuItem>
        <SidebarMenuItem href="#" :active="false">
            <template #icon>
                <LifeBuoy :size="16" />
            </template>
            Bantuan
        </SidebarMenuItem>
    </SidebarMenu>
</template>
