<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Clock } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import TracksActionBar from '@/components/tracks/TracksActionBar.vue';
import TrackRow from '@/components/tracks/TrackRow.vue';
import type { Track } from '@/types/track';

const page = usePage();
const tracks = computed<Track[]>(() => (page.props.tracks as Track[]) ?? []);

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

const currentTrackId = ref<number | null>(null);
const isPlaying = ref(false);

const playTrack = (id: number | null) => {
    if (id === null) {
        return;
    }
    if (currentTrackId.value === id) {
        isPlaying.value = !isPlaying.value;
    } else {
        currentTrackId.value = id;
        isPlaying.value = true;
    }
};
</script>

<template>
    <div class="min-h-screen bg-[#121212] text-white">
        <!-- Header Gradient Background -->
        <div class="absolute top-0 left-0 right-0 h-[340px] bg-gradient-to-b from-emerald-800/80 to-[#121212] pointer-events-none"></div>

        <div class="relative z-10 px-6 pb-8 pt-6">
            <!-- Playlist Header -->
            <header class="flex flex-col gap-6 sm:flex-row sm:items-end">
                <div class="h-[180px] w-[180px] shrink-0 bg-gradient-to-br from-emerald-600 to-emerald-900 shadow-2xl shadow-black/50 flex items-center justify-center rounded-md">
                    <span class="text-6xl">♫</span>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Playlist</span>
                    <h1 class="text-4xl font-black sm:text-7xl tracking-tighter">Mes Tracks</h1>
                    <div class="mt-2 flex items-center gap-2 text-sm font-medium text-white/90">
                        <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center text-black font-bold text-xs">U</div>
                        <span class="hover:underline cursor-pointer">Utilisateur</span>
                        <span>•</span>
                        <span>{{ tracks.length }} titres</span>
                        <span class="text-white/70">, env. {{ Math.floor(tracks.length * 3.5) }} min</span>
                    </div>
                </div>
            </header>

            <!-- Action Bar -->
            <TracksActionBar @play="() => playTrack(tracks[0]?.id ?? null)" />

            <!-- Tracks Table -->
            <div class="mt-4">
                <!-- Table Header -->
                <div class="sticky top-0 z-20 grid grid-cols-[16px_4fr_3fr_2fr_minmax(60px,1fr)] gap-4 border-b border-white/10 bg-[#121212] px-4 py-2 text-sm font-medium text-[#b3b3b3] uppercase tracking-wider">
                    <div class="text-center">#</div>
                    <div>Titre</div>
                    <div class="hidden sm:block">Album</div>
                    <div class="hidden md:block">Date d'ajout</div>
                    <div class="flex justify-end pr-8"><Clock :size="16" /></div>
                </div>

                <!-- Table Body -->
                <div class="mt-2 flex flex-col">
                    <TrackRow
                        v-for="(track, index) in tracks"
                        :key="track.id"
                        :track="track"
                        :index="index"
                        :is-current="currentTrackId === track.id"
                        :is-playing="isPlaying"
                        :formatted-date="formatDate(track.created_at)"
                        :formatted-duration="formatDuration(track.duration)"
                        @play="playTrack"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom Scrollbar if needed */
::-webkit-scrollbar {
    width: 12px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background-color: hsla(0,0%,100%,.3);
    border-radius: 20px;
    border: 3px solid transparent;
    background-clip: content-box;
}
::-webkit-scrollbar-thumb:hover {
    background-color: hsla(0,0%,100%,.5);
}
</style>

