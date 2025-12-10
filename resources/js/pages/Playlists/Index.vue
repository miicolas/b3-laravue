<script setup lang="ts">
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Playlist } from '@/types/playlist';
import type { BreadcrumbItem } from '@/types';

const page = usePage();
const playlists = computed<Playlist[]>(() => (page.props.playlists as Playlist[]) ?? []);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mes Playlists',
        href: '/playlists',
    },
];

const defaultImage = 'https://ui-avatars.com/api/?name=Playlist&background=10b981&color=fff';
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-[#121212] text-white p-6">
            <header class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Mes Playlists</h1>
                <Link
                    href="/playlists/create"
                    class="bg-emerald-500 hover:bg-emerald-400 text-black font-bold py-2 px-6 rounded-full transition"
                >
                    Créer une playlist
                </Link>
            </header>

            <div v-if="playlists.length === 0" class="text-center py-20 text-gray-400">
                <p class="text-xl mb-4">Vous n'avez pas encore de playlist.</p>
                <Link
                    href="/playlists/create"
                    class="text-emerald-500 hover:underline"
                >
                    Commencez par en créer une !
                </Link>
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <Link
                    v-for="playlist in playlists"
                    :key="playlist.id"
                    :href="`/playlists/${playlist.slug}`"
                    class="bg-[#181818] p-4 rounded-md hover:bg-[#282828] transition group"
                >
                    <div class="aspect-square bg-[#333] mb-4 rounded-md overflow-hidden relative shadow-lg">
                        <img
                            :src="playlist.image ? `/storage/${playlist.image}` : defaultImage"
                            :alt="playlist.name"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                             <div class="h-12 w-12 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                             </div>
                        </div>
                    </div>
                    <h3 class="font-bold truncate">{{ playlist.name }}</h3>
                    <div class="flex items-center justify-between mt-1 text-sm text-gray-400">
                        <span class="truncate pr-2 w-full">{{ playlist.description || 'Aucune description' }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1 uppercase font-semibold tracking-wider">
                        {{ playlist.tracks_count }} titres
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
