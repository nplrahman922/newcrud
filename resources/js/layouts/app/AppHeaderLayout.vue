<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLogo from '@/components/AppLogo.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-background pt-6 sm:justify-center sm:pt-0">
        <div>
            <Link :href="route('dashboard')">
                <AppLogo class="h-14 w-auto" />
            </Link>
        </div>

        <div class="mt-6 w-full overflow-hidden bg-card px-6 py-4 sm:max-w-md sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
