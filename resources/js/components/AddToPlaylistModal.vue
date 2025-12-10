<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import type { Track } from '@/types/track';

const props = defineProps<{
    show: boolean;
    track: Track | null;
    playlists: { id: number; name: string; slug: string }[];
}>();

const emit = defineEmits(['close']);

const addToPlaylist = (playlistSlug: string) => {
    if (!props.track) return;

    useForm({
        track_id: props.track.id
    }).post(`/playlists/${playlistSlug}/tracks`, {
        preserveScroll: true,
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <Dialog :open="show" @update:open="$emit('close')">
        <DialogContent class="sm:max-w-md bg-[#282828] border-none text-white">
            <DialogHeader>
                <DialogTitle>Ajouter à la playlist</DialogTitle>
            </DialogHeader>
            <div class="flex flex-col gap-2 mt-4 max-h-[60vh] overflow-y-auto pr-2">
                <button
                    v-for="playlist in playlists"
                    :key="playlist.id"
                    class="w-full text-left p-3 hover:bg-white/10 rounded flex items-center gap-3 transition group"
                    @click="addToPlaylist(playlist.slug)"
                >
                    <div class="h-10 w-10 bg-[#333] flex items-center justify-center rounded shrink-0">
                        ♫
                    </div>
                    <span class="font-medium truncate group-hover:text-emerald-500 transition-colors">{{ playlist.name }}</span>
                </button>
                <div v-if="playlists.length === 0" class="text-center text-gray-400 py-4">
                    Aucune playlist disponible.
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
