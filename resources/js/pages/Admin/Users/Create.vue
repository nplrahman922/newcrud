<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import InputError from '@/components/InputError.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    access_duration: '1_month',
});

const submit = () => {
    form.post(route('admin.users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Tambah Pengguna" />

    <AppLayout>
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900 dark:text-white">
                    Tambah Pengguna Baru
                </h1>
                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <Label for="name">Nama</Label>
                        <Input id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <Label for="email">Email</Label>
                        <Input id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <Label for="password">Password</Label>
                        <Input id="password" v-model="form.password" type="password" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div>
                        <Label for="password_confirmation">Konfirmasi Password</Label>
                        <Input id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                    <div>
                        <Label for="role">Peran</Label>
                        <Select v-model="form.role">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih peran" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="user">User</SelectItem>
                                <SelectItem value="admin">Admin</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>
                    <div>
                        <Label for="access_duration">Masa Berlaku Akses</Label>
                        <Select v-model="form.access_duration">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih durasi" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="1_month">1 Bulan</SelectItem>
                                <SelectItem value="3_months">3 Bulan</SelectItem>
                                <SelectItem value="6_months">6 Bulan</SelectItem>
                                <SelectItem value="1_year">1 Tahun</SelectItem>
                                <SelectItem value="unlimited">Tidak Terbatas</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.access_duration" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Simpan</Button>
                        <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Tersimpan.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
