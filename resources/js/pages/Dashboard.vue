<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { defineProps, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import { useAppearance } from '@/composables/useAppearance';

// --- BAGIAN BARU UNTUK GRAFIK ---

// Mengambil status tema (light/dark) dari composable
const { appearance } = useAppearance();

// Definisikan interface untuk data yang kita terima dari Laravel
interface UserStat {
    date: string;
    count: number;
}

// Terima prop 'userRegistrationStats' dari controller
const props = defineProps<{
    userRegistrationStats: UserStat[],
    breadcrumbs: BreadcrumbItem[],
}>();

// Gunakan 'computed' untuk memformat data agar sesuai dengan format ApexCharts
const chartData = computed(() => {
    if (!props.userRegistrationStats || props.userRegistrationStats.length === 0) {
        return { dates: [], counts: [] };
    }
    const dates = props.userRegistrationStats.map(stat => new Date(stat.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
    const counts = props.userRegistrationStats.map(stat => stat.count);
    return { dates, counts };
});

// Konfigurasi untuk grafik yang sudah disesuaikan dengan tema
const chartOptions = computed(() => ({
    chart: {
        id: 'user-registrations',
        type: 'line', // <-- UBAH INI
        height: '100%',
        parentHeightOffset: 0,
        toolbar: {
            show: false,
        },
        fontFamily: 'Instrument Sans, sans-serif',
    },
    theme: {
        // Otomatis ganti tema grafik berdasarkan tema aplikasi
        mode: appearance.value === 'dark' ? 'dark' : 'light'
    },
    colors: ['hsl(var(--primary))'],
    grid: {
        borderColor: 'hsl(var(--border) / 0.5)',
        strokeDashArray: 3,
        padding: {
            left: 20,
            bottom: 5,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '50%',
            borderRadius: 4,
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    xaxis: {
        categories: chartData.value.dates,
        labels: {
            style: {
                colors: 'hsl(var(--muted-foreground))',
                fontSize: '12px',
            },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: {
        labels: {
            style: {
                colors: 'hsl(var(--muted-foreground))',
                fontSize: '12px',
            },
        },
    },
    title: {
        text: 'Pengguna Baru',
        align: 'left',
        margin: 20,
        style: {
            fontSize: '16px',
            fontWeight: '600',
            // Warna teks judul diambil dari variabel CSS tema
            color: 'hsl(var(--foreground))'
        },
    },
    tooltip: {
        theme: appearance.value === 'dark' ? 'dark' : 'light',
        y: {
            formatter: (val: number) => `${val} pengguna`
        }
    }
}));

// Data series untuk grafik
const series = computed(() => [{
    name: 'Pengguna Baru',
    data: chartData.value.counts,
}]);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 lg:p-6">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-card dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-card dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-card p-2 dark:border-sidebar-border sm:p-4">
                    <VueApexCharts
                        v-if="userRegistrationStats.length > 0"
                        type="bar"
                        :options="chartOptions"
                        :series="series"
                        height="100%"
                    />
                    <div v-else class="flex h-full items-center justify-center text-sm text-muted-foreground">
                        Tidak ada data pendaftaran.
                    </div>
                </div>
            </div>

            <div class="relative min-h-[400px] flex-1 rounded-xl border border-sidebar-border/70 bg-card dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
