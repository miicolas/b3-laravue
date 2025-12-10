<script setup lang="ts">
import { usePage, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import TrackRow from '@/components/tracks/TrackRow.vue';
import AddToPlaylistModal from '@/components/AddToPlaylistModal.vue';
import type { Track } from '@/types/track';
import type { Playlist } from '@/types/playlist';
import { usePlayer } from '@/composables/usePlayer';
import type { BreadcrumbItem } from '@/types';
import { Trash } from 'lucide-vue-next';

const page = usePage();
const playlist = computed<Playlist>(() => page.props.playlist as Playlist);
const tracks = computed<Track[]>(() => (page.props.tracks as Track[]) ?? []);
const userPlaylists = computed(() => (page.props.playlists as { id: number; name: string; slug: string }[]) ?? []); // Assuming global share or added to controller

const {
    currentTrack,
    isPlaying,
    setPlaylist,
    togglePlay,
} = usePlayer();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mes Playlists',
        href: '/playlists',
    },
    {
        title: playlist.value.name,
        href: `/playlists/${playlist.value.slug}`,
    },
];

const defaultImage = 'https://ui-avatars.com/api/?name=Playlist&background=10b981&color=fff';

const formatDuration = (duration?: string | null): string => {
    if (!duration) return '—';
    return duration;
};

const formatDate = (date?: string | null): string => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const playTrack = (id: number | null) => {
    if (id === null) return;
    const trackIndex = tracks.value.findIndex(t => t.id === id);
    if (trackIndex === -1) return;

    if (currentTrack.value?.id === id) {
        togglePlay();
    } else {
        setPlaylist(tracks.value, trackIndex);
    }
};

const handlePlayAll = () => {
    if (tracks.value.length > 0) {
        setPlaylist(tracks.value, 0);
    }
};

const removeFromPlaylist = (track: Track) => {
    if (confirm('Voulez-vous vraiment retirer ce titre de la playlist ?')) {
        router.delete(`/playlists/${playlist.value.slug}/tracks/${track.slug}`, {
            preserveScroll: true,
        });
    }
};

const deletePlaylist = () => {
    if (confirm('Voulez-vous vraiment supprimer cette playlist ?')) {
        router.delete(`/playlists/${playlist.value.slug}`);
    }
};

// Add to playlist modal logic (reused)
const showAddToPlaylistModal = ref(false);
const trackToAdd = ref<Track | null>(null);

const openAddToPlaylistModal = (track: Track) => {
    trackToAdd.value = track;
    showAddToPlaylistModal.value = true;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-[#121212] text-white">
            <!-- Header Gradient -->
            <div class="absolute top-0 left-0 right-0 h-[340px] bg-gradient-to-b from-emerald-900/50 to-[#121212] pointer-events-none"></div>

            <div class="relative z-10 px-6 pb-8 pt-6">
                <!-- Playlist Header -->
                <header class="flex flex-col gap-6 sm:flex-row sm:items-end mb-6">
                    <div class="h-[232px] w-[232px] shrink-0 shadow-2xl shadow-black/50 rounded-md overflow-hidden">
                         <img
                            :src="playlist.image ? `/storage/${playlist.image}` : defaultImage"
                            :alt="playlist.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div class="flex flex-col gap-2 w-full">
                        <span class="text-xs font-bold uppercase tracking-wider">Playlist</span>
                        <h1 class="text-4xl font-black sm:text-7xl tracking-tighter sm:whitespace-nowrap">{{ playlist.name }}</h1>
                        <p class="text-sm text-white/70 mt-2 max-w-xl">{{ playlist.description }}</p>
                        <div class="mt-2 flex items-center gap-2 text-sm font-medium text-white/90">
                             <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center text-black font-bold text-xs">U</div>
                            <span class="hover:underline cursor-pointer">Utilisateur</span>
                            <span>•</span>
                            <span>{{ tracks.length }} titres</span>
                        </div>
                    </div>
                </header>

                <!-- Action Bar -->
                <div class="flex items-center gap-4 py-4 mb-4">
                     <!-- Play Button -->
                    <button
                        class="h-14 w-14 rounded-full bg-emerald-500 hover:scale-105 transition flex items-center justify-center shadow-lg text-black"
                        @click="handlePlayAll"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </button>

                    <!-- Edit Button -->
                     <Link
                        class="text-sm font-bold text-gray-400 hover:text-white uppercase tracking-wider border border-gray-600 rounded px-4 py-1 hover:border-white transition"
                        :href="`/playlists/${playlist.slug}/edit`"
                    >
                        Modifier
                    </Link>

                    <!-- Delete Button -->
                    <button
                        @click="deletePlaylist"
                        class="text-gray-400 hover:text-red-500 transition ml-auto"
                        title="Supprimer la playlist"
                    >
                        <Trash class="h-6 w-6" />
                    </button>
                </div>

                <!-- Tracks List -->
                <div class="mt-4">
                     <!-- Header Row -->
                    <div class="grid grid-cols-[16px_4fr_3fr_2fr_minmax(70px,1fr)_minmax(90px,1fr)] items-center gap-4 px-4 py-2 border-b border-white/10 text-sm text-[#b3b3b3] uppercase tracking-wider mb-2 sticky top-[64px] bg-[#121212] z-20">
                        <div class="text-center">#</div>
                        <div>Titre</div>
                        <div class="hidden sm:block">Album</div>
                        <div class="hidden md:block">Date d'ajout</div>
                        <div class="flex justify-end pr-4">Durée</div>
                        <div class="flex justify-end pr-2"><span class="sr-only">Actions</span></div>
                    </div>

                    <div class="flex flex-col">
                        <TrackRow
                            v-for="(track, index) in tracks"
                            :key="track.id"
                            :track="track"
                            :index="index"
                            :is-current="currentTrack?.id === track.id"
                            :is-playing="isPlaying && currentTrack?.id === track.id"
                            :formatted-date="formatDate(track.created_at)"
                            :formatted-duration="formatDuration(track.duration)"
                            :is-admin="false"
                            context="playlist"
                            @play="playTrack"
                            @remove-from-playlist="removeFromPlaylist"
                            @add-to-playlist="openAddToPlaylistModal"
                        />
                        <div v-if="tracks.length === 0" class="py-10 text-center text-gray-400">
                            Cette playlist est vide.
                            <br>
                            <Link href="/tracks" class="text-emerald-500 hover:underline">Ajouter des titres</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <AddToPlaylistModal
            :show="showAddToPlaylistModal"
            :track="trackToAdd"
            :playlists="userPlaylists"
            @close="showAddToPlaylistModal = false"
        />
    </AppLayout>
</template>
