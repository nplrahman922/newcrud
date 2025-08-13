<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineProps, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { useAppearance } from '@/composables/useAppearance';

import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Users, Package, DollarSign, CreditCard } from 'lucide-vue-next';

// --- INTERFACE & PROPS ---
interface UserStat {
    date: string;
    count: number;
}
interface User {
    id: number;
    name: string;
    email: string;
    avatar_url?: string; // Asumsikan ada avatar, jika tidak ada fallback akan dipakai
}
interface Product {
    id: number;
    name: string;
    description: string | null;
}

const props = defineProps<{
    breadcrumbs: BreadcrumbItem[],
    userRegistrationStats: UserStat[],
    totalUsers: number,
    totalProducts: number,
    recentUsers: User[],
    recentProducts: Product[],
}>();


// --- KONFIGURASI GRAFIK (TETAP SAMA) ---
const { appearance } = useAppearance();

const chartData = computed(() => {
    if (!props.userRegistrationStats || props.userRegistrationStats.length === 0) {
        return { dates: [], counts: [] };
    }
    const dates = props.userRegistrationStats.map(stat => new Date(stat.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
    const counts = props.userRegistrationStats.map(stat => stat.count);
    return { dates, counts };
});

const chartOptions = computed(() => ({
    chart: { id: 'user-registrations', type: 'line', height: '100%', parentHeightOffset: 0, toolbar: { show: false }, fontFamily: 'Instrument Sans, sans-serif' },
    theme: { mode: appearance.value === 'dark' ? 'dark' : 'light' },
    colors: ['hsl(var(--primary))'],
    grid: { borderColor: 'hsl(var(--border) / 0.5)', strokeDashArray: 3, padding: { left: 0, bottom: -10 } },
    stroke: { width: 2, curve: 'smooth' },
    markers: { size: 0 },
    xaxis: { categories: chartData.value.dates, labels: { style: { colors: 'hsl(var(--muted-foreground))', fontSize: '12px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
    yaxis: { labels: { style: { colors: 'hsl(var(--muted-foreground))', fontSize: '12px' } }, min: 0 },
    tooltip: { theme: appearance.value === 'dark' ? 'dark' : 'light', y: { formatter: (val: number) => `${val} pengguna` } }
}));

const series = computed(() => [{ name: 'Pengguna Baru', data: chartData.value.counts }]);

// Fungsi untuk membuat inisial dari nama
const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Pengguna</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ totalUsers }}</div>
                        <p class="text-xs text-muted-foreground">+20.1% dari bulan lalu</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Produk</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ totalProducts }}</div>
                        <p class="text-xs text-muted-foreground">+180.1% dari bulan lalu</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Penjualan</CardTitle>
                        <CreditCard class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">+12,234</div>
                        <p class="text-xs text-muted-foreground">+19% dari bulan lalu</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Pendapatan</CardTitle>
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">Rp 45,231,890</div>
                        <p class="text-xs text-muted-foreground">+201 sejak kemarin</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-7 lg:gap-6">
                <Card class="lg:col-span-4">
                    <CardHeader>
                        <CardTitle>Pendaftaran Pengguna (30 Hari Terakhir)</CardTitle>
                    </CardHeader>
                    <CardContent class="pl-2 pr-4">
                        <div class="h-[300px]">
                            <VueApexCharts :options="chartOptions" :series="series" height="100%" />
                        </div>
                    </CardContent>
                </Card>

                <Card class="lg:col-span-3">
                    <CardHeader>
                        <CardTitle>Pengguna Baru Mendaftar</CardTitle>
                        <CardDescription>Ada {{ recentUsers.length }} pengguna baru mendaftar.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-for="user in recentUsers" :key="user.id" class="flex items-center">
                            <Avatar class="h-9 w-9">
                                <AvatarImage :src="user.avatar_url || '' "  alt="Avatar" />
                                <AvatarFallback>{{ getInitials(user.name) }}</AvatarFallback>
                            </Avatar>
                            <div class="ml-4 space-y-1">
                                <p class="text-sm font-medium leading-none">{{ user.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
