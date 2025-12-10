<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { Playlist } from '@/types/playlist';

const props = defineProps<{
    playlist: Playlist;
}>();

const form = useForm({
    name: props.playlist.name,
    description: props.playlist.description || '',
    image: null as File | null,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mes Playlists',
        href: '/playlists',
    },
    {
        title: props.playlist.name,
        href: `/playlists/${props.playlist.slug}`,
    },
    {
        title: 'Modifier',
        href: `/playlists/${props.playlist.slug}/edit`,
    },
];

const submit = () => {
    form.put(`/playlists/${props.playlist.slug}`, {
        onSuccess: () => {
            // Reset only the image field after success
            form.image = null;
        },
    });
};

const handleImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
    }
};

const currentImageUrl = props.playlist.image ? `/storage/${props.playlist.image}` : null;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-[#121212] text-white flex items-center justify-center p-6">
            <div class="w-full max-w-2xl bg-[#181818] p-8 rounded-xl shadow-2xl">
                <h1 class="text-3xl font-bold mb-8">Modifier la playlist</h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Image Upload -->
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                         <div class="w-40 h-40 bg-[#282828] shrink-0 rounded shadow-inner flex items-center justify-center text-5xl text-gray-600 relative overflow-hidden group">
                             <span v-if="!form.image && !currentImageUrl">♫</span>
                             <img 
                                v-else-if="form.image" 
                                :src="URL.createObjectURL(form.image)" 
                                class="absolute inset-0 w-full h-full object-cover" 
                            />
                             <img 
                                v-else-if="currentImageUrl" 
                                :src="currentImageUrl" 
                                class="absolute inset-0 w-full h-full object-cover" 
                            />
                             
                             <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                 </svg>
                                 <input type="file" @change="handleImageChange" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" />
                             </div>
                         </div>

                         <div class="flex-1 space-y-4 w-full">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nom</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full bg-[#333] border-transparent focus:border-white focus:ring-0 rounded p-3 text-white placeholder-gray-500"
                                    placeholder="Ma Super Playlist"
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Description (Optionnel)</label>
                                <textarea
                                    v-model="form.description"
                                    class="w-full bg-[#333] border-transparent focus:border-white focus:ring-0 rounded p-3 text-white placeholder-gray-500 min-h-[100px]"
                                    placeholder="Une description pour votre playlist..."
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                            </div>
                         </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <Link :href="`/playlists/${playlist.slug}`" class="px-6 py-3 font-bold text-gray-400 hover:text-white transition mr-4">Annuler</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-white text-black font-bold py-3 px-8 rounded-full hover:scale-105 transition disabled:opacity-70 disabled:hover:scale-100"
                        >
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
