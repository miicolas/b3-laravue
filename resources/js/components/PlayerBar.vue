<script setup lang="ts">
import { Play, SkipBack, SkipForward, Repeat, Shuffle, Mic2, ListMusic, MonitorSpeaker, Volume2, Heart } from 'lucide-vue-next';
import { ref } from 'vue';

const isPlaying = ref(false);
const volume = ref(75);
const progress = ref(33);

const togglePlay = () => {
    isPlaying.value = !isPlaying.value;
};
</script>

<template>
    <div class="fixed bottom-0 left-0 right-0 z-50 grid h-[90px] grid-cols-3 items-center border-t border-[#282828] bg-[#181818] px-4 text-[#b3b3b3]">
        <!-- Track Info -->
        <div class="flex items-center justify-start gap-4">
            <div class="h-14 w-14 overflow-hidden rounded bg-[#282828] shadow-lg">
                <img src="https://picsum.photos/seed/current/200" alt="Cover" class="h-full w-full object-cover opacity-80" />
            </div>
            <div class="flex flex-col justify-center">
                <a href="#" class="text-sm font-normal text-white hover:underline">Titre de la chanson</a>
                <a href="#" class="text-xs text-[#b3b3b3] hover:text-white hover:underline">Artiste</a>
            </div>
            <button class="ml-2 text-[#b3b3b3] hover:text-white">
                <Heart :size="16" />
            </button>
        </div>

        <!-- Player Controls -->
        <div class="flex flex-col items-center justify-center gap-2">
            <div class="flex items-center gap-6">
                <button class="text-[#b3b3b3] hover:text-white">
                    <Shuffle :size="16" />
                </button>
                <button class="text-[#b3b3b3] hover:text-white">
                    <SkipBack :size="20" class="fill-current" />
                </button>
                <button 
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-black transition hover:scale-105 active:scale-100"
                    @click="togglePlay"
                >
                    <Play v-if="!isPlaying" :size="20" class="fill-current ml-0.5" />
                    <div v-else class="h-3 w-3 bg-black"></div>
                </button>
                <button class="text-[#b3b3b3] hover:text-white">
                    <SkipForward :size="20" class="fill-current" />
                </button>
                <button class="text-[#b3b3b3] hover:text-white">
                    <Repeat :size="16" />
                </button>
            </div>
            
            <div class="flex w-full max-w-md items-center gap-2 text-xs font-medium text-[#a7a7a7]">
                <span>1:23</span>
                <div class="group relative h-1 flex-1 rounded-full bg-[#4d4d4d]">
                    <div class="absolute h-full rounded-full bg-white group-hover:bg-[#1db954]" :style="{ width: `${progress}%` }"></div>
                    <div class="absolute -top-1 hidden h-3 w-3 rounded-full bg-white group-hover:block" :style="{ left: `${progress}%` }"></div>
                </div>
                <span>3:45</span>
            </div>
        </div>

        <!-- Volume / Extra Controls -->
        <div class="flex items-center justify-end gap-3">
            <button class="text-[#b3b3b3] hover:text-white">
                <Mic2 :size="16" />
            </button>
            <button class="text-[#b3b3b3] hover:text-white">
                <ListMusic :size="16" />
            </button>
            <button class="text-[#b3b3b3] hover:text-white">
                <MonitorSpeaker :size="16" />
            </button>
            <div class="flex w-32 items-center gap-2">
                <Volume2 :size="16" />
                <div class="group relative h-1 flex-1 rounded-full bg-[#4d4d4d]">
                    <div class="absolute h-full rounded-full bg-white group-hover:bg-[#1db954]" :style="{ width: `${volume}%` }"></div>
                    <div class="absolute -top-1 hidden h-3 w-3 rounded-full bg-white group-hover:block" :style="{ left: `${volume}%` }"></div>
                </div>
            </div>
        </div>
    </div>
</template>
