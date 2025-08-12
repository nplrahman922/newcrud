<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps({
    product: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.product.name,
    description: props.product.description,
    photo: null,
    video: null,
});

const submit = () => {
    form.post(route('products.update', props.product.id));
};
</script>

<template>
    <Head title="Edit Product" />

    <AppLayout>
         <div class="p-4 sm:p-6 lg:p-8">
            <div class="max-w-md mx-auto">
                <h1 class="text-xl font-semibold text-gray-900">Edit Product</h1>
                <form @submit.prevent="submit" class="mt-8 space-y-6">
                    <div>
                        <Label for="name">Name</Label>
                        <Input id="name" type="text" v-model="form.name" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <Label for="description">Description</Label>
                         <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                     <div>
                        <Label for="photo">Photo</Label>
                        <Input id="photo" type="file" @input="form.photo = $event.target.files[0]" class="mt-1 block w-full" />
                    </div>
                     <div>
                        <Label for="video">Video</Label>
                        <Input id="video" type="file" @input="form.video = $event.target.files[0]" class="mt-1 block w-full" />
                    </div>
                    <div class="flex items-center justify-end">
                        <Button type="submit" :disabled="form.processing">Update</Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>