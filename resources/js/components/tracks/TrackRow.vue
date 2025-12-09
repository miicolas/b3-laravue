<script setup lang="ts">
import { Pause, Play } from 'lucide-vue-next';
import type { Track } from '@/types/track';

const props = defineProps<{
    track: Track;
    index: number;
    isCurrent: boolean;
    isPlaying: boolean;
    formattedDate: string;
    formattedDuration: string;
}>();

const emit = defineEmits<{
    (e: 'play', id: number): void;
}>();

const handlePlay = () => {
    emit('play', props.track.id);
};
</script>

<template>
    <div
        class="group grid grid-cols-[16px_4fr_3fr_2fr_minmax(60px,1fr)] items-center gap-4 rounded-md px-4 py-2 text-sm text-[#b3b3b3] hover:bg-white/10 transition-colors"
        @dblclick="handlePlay"
    >
        <div class="relative flex items-center justify-center h-4 w-4">
            <span class="group-hover:hidden" :class="{ 'text-[#1db954]': isCurrent }">{{ index + 1 }}</span>
            <button class="hidden group-hover:block text-white" @click="handlePlay">
                <Pause v-if="isCurrent && isPlaying" :size="16" class="fill-current" />
                <Play v-else :size="16" class="fill-current" />
            </button>
            <img
                v-if="isCurrent && isPlaying"
                src="https://open.spotifycdn.com/cdn/images/equaliser-animated-green.f93a2ef4.gif"
                class="absolute h-3 w-3 group-hover:hidden"
            />
        </div>

        <div class="flex items-center gap-4 overflow-hidden">
            <img
                v-if="track.thumbnail"
                :src="track.thumbnail"
                alt="Cover"
                class="h-10 w-10 rounded shadow-sm object-cover"
            />
            <div v-else class="h-10 w-10 rounded bg-[#282828] flex items-center justify-center text-xs">
                ♫
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="truncate font-medium text-white" :class="{ 'text-[#1db954]': isCurrent }">
                    {{ track.title }}
                </span>
                <span class="truncate text-xs group-hover:text-white transition-colors">{{ track.artist }}</span>
            </div>
        </div>

        <div class="hidden sm:block truncate group-hover:text-white transition-colors">
            {{ track.album || '—' }}
        </div>

        <div class="hidden md:block truncate">
            {{ formattedDate }}
        </div>

        <div class="flex justify-end pr-4 font-variant-numeric tabular-nums">
            {{ formattedDuration }}
        </div>
    </div>
</template>
