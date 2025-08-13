<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { MoreHorizontal } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { format } from 'date-fns';

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    access_expires_at: string | null;
    created_at: string;
}

interface PaginatedUsers {
    data: User[];
    // tambahkan properti paginasi lainnya jika diperlukan
}

const props = defineProps<{
    users: PaginatedUsers;
}>();

const deleteUser = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        router.delete(route('admin.users.destroy', id));
    }
};

const formatDate = (dateString: string | null) => {
    if (!dateString) return 'Tidak Terbatas';
    return format(new Date(dateString), 'dd MMM yyyy');
};
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <AppLayout>
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">
                        Manajemen Pengguna
                    </h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">
                        Daftar semua pengguna di platform Akademi Arsitek.
                    </p>
                </div>
                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                    <Link :href="route('admin.users.create')">
                        <Button>Tambah Pengguna</Button>
                    </Link>
                </div>
            </div>

            <Card class="mt-8">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nama</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Peran</TableHead>
                            <TableHead>Akses Berakhir</TableHead>
                            <TableHead><span class="sr-only">Aksi</span></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="font-medium">{{ user.name }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge :variant="user.role === 'admin' ? 'default' : 'secondary'">
                                    {{ user.role }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ formatDate(user.access_expires_at) }}</TableCell>
                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="h-8 w-8 p-0">
                                            <span class="sr-only">Buka menu</span>
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuLabel>Aksi</DropdownMenuLabel>
                                        <Link :href="route('admin.users.edit', user.id)">
                                            <DropdownMenuItem>Edit</DropdownMenuItem>
                                        </Link>
                                        <DropdownMenuItem @click="deleteUser(user.id)">Hapus</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </AppLayout>
</template>
