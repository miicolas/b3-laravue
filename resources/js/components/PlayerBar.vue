<script setup lang="ts">
import { Play, Pause, SkipBack, SkipForward, Repeat, Shuffle, Mic2, ListMusic, MonitorSpeaker, Volume2, VolumeX } from 'lucide-vue-next';
import { usePlayer } from '@/composables/usePlayer';
import { ref } from 'vue';

const {
    currentTrack,
    isPlaying,
    isLoading,
    volume,
    progress,
    formattedCurrentTime,
    formattedDuration,
    togglePlay,
    next,
    previous,
    seekToPercent,
    setVolume,
} = usePlayer();

const progressBar = ref<HTMLDivElement | null>(null);
const volumeBar = ref<HTMLDivElement | null>(null);

const handleProgressClick = (event: MouseEvent) => {
    if (!progressBar.value) return;
    const rect = progressBar.value.getBoundingClientRect();
    const percent = ((event.clientX - rect.left) / rect.width) * 100;
    seekToPercent(Math.max(0, Math.min(100, percent)));
};

const handleVolumeClick = (event: MouseEvent) => {
    if (!volumeBar.value) return;
    const rect = volumeBar.value.getBoundingClientRect();
    const percent = ((event.clientX - rect.left) / rect.width) * 100;
    setVolume(Math.max(0, Math.min(100, percent)));
};

const toggleMute = () => {
    if (volume.value > 0) {
        setVolume(0);
    } else {
        setVolume(75);
    }
};
</script>

<template>
    <div class="fixed bottom-0 left-0 right-0 z-50 grid h-[90px] grid-cols-3 items-center border-t border-[#282828] bg-[#181818] px-4 text-[#b3b3b3]">
        <!-- Track Info -->
        <div class="flex items-center justify-start gap-4">
            <template v-if="currentTrack">
                <div class="h-14 w-14 overflow-hidden rounded bg-[#282828] shadow-lg">
                    <img 
                        v-if="currentTrack.thumbnail_url" 
                        :src="currentTrack.thumbnail_url" 
                        :alt="currentTrack.title" 
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="h-full w-full flex items-center justify-center text-2xl">♫</div>
                </div>
                <div class="flex flex-col justify-center overflow-hidden">
                    <span class="text-sm font-normal text-white truncate max-w-[180px]">{{ currentTrack.title }}</span>
                    <span class="text-xs text-[#b3b3b3] truncate max-w-[180px]">{{ currentTrack.artist }}</span>
                </div>
            </template>
            <template v-else>
                <div class="h-14 w-14 overflow-hidden rounded bg-[#282828] shadow-lg opacity-50">
                    <div class="h-full w-full flex items-center justify-center text-2xl">♫</div>
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-sm font-normal text-white/50">Aucun titre</span>
                    <span class="text-xs text-[#b3b3b3]/50">—</span>
                </div>
            </template>
        </div>

        <!-- Player Controls -->
        <div class="flex flex-col items-center justify-center gap-2">
            <div class="flex items-center gap-6">
                <button class="text-[#b3b3b3] hover:text-white transition-colors">
                    <Shuffle :size="16" />
                </button>
                <button 
                    class="text-[#b3b3b3] hover:text-white transition-colors"
                    @click="previous"
                >
                    <SkipBack :size="20" class="fill-current" />
                </button>
                <button 
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-black transition hover:scale-105 active:scale-100 disabled:opacity-50"
                    :disabled="!currentTrack || isLoading"
                    @click="togglePlay"
                >
                    <div v-if="isLoading" class="h-4 w-4 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
                    <Pause v-else-if="isPlaying" :size="20" class="fill-current" />
                    <Play v-else :size="20" class="fill-current ml-0.5" />
                </button>
                <button 
                    class="text-[#b3b3b3] hover:text-white transition-colors"
                    @click="next"
                >
                    <SkipForward :size="20" class="fill-current" />
                </button>
                <button class="text-[#b3b3b3] hover:text-white transition-colors">
                    <Repeat :size="16" />
                </button>
            </div>
            
            <div class="flex w-full max-w-md items-center gap-2 text-xs font-medium text-[#a7a7a7]">
                <span class="w-10 text-right tabular-nums">{{ formattedCurrentTime }}</span>
                <div 
                    ref="progressBar"
                    class="group relative h-1 flex-1 rounded-full bg-[#4d4d4d] cursor-pointer"
                    @click="handleProgressClick"
                >
                    <div 
                        class="absolute h-full rounded-full bg-white group-hover:bg-[#1db954] transition-colors" 
                        :style="{ width: `${progress}%` }"
                    ></div>
                    <div 
                        class="absolute -top-1 h-3 w-3 rounded-full bg-white opacity-0 group-hover:opacity-100 transition-opacity" 
                        :style="{ left: `calc(${progress}% - 6px)` }"
                    ></div>
                </div>
                <span class="w-10 tabular-nums">{{ formattedDuration }}</span>
            </div>
        </div>

        <!-- Volume / Extra Controls -->
        <div class="flex items-center justify-end gap-3">
            <button class="text-[#b3b3b3] hover:text-white transition-colors">
                <Mic2 :size="16" />
            </button>
            <button class="text-[#b3b3b3] hover:text-white transition-colors">
                <ListMusic :size="16" />
            </button>
            <button class="text-[#b3b3b3] hover:text-white transition-colors">
                <MonitorSpeaker :size="16" />
            </button>
            <div class="flex w-32 items-center gap-2">
                <button @click="toggleMute" class="text-[#b3b3b3] hover:text-white transition-colors">
                    <VolumeX v-if="volume === 0" :size="16" />
                    <Volume2 v-else :size="16" />
                </button>
                <div 
                    ref="volumeBar"
                    class="group relative h-1 flex-1 rounded-full bg-[#4d4d4d] cursor-pointer"
                    @click="handleVolumeClick"
                >
                    <div 
                        class="absolute h-full rounded-full bg-white group-hover:bg-[#1db954] transition-colors" 
                        :style="{ width: `${volume}%` }"
                    ></div>
                    <div 
                        class="absolute -top-1 h-3 w-3 rounded-full bg-white opacity-0 group-hover:opacity-100 transition-opacity" 
                        :style="{ left: `calc(${volume}% - 6px)` }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
