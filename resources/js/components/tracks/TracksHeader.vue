<script setup lang="ts">
defineProps<{
    trackCount: number;
    estimatedMinutes: number;
}>();
</script>

<template>
    <header class="flex flex-col gap-6 sm:flex-row sm:items-end">
        <div
            class="h-[180px] w-[180px] shrink-0 bg-gradient-to-br from-emerald-600 to-emerald-900 shadow-2xl shadow-black/50 flex items-center justify-center rounded-md"
        >
            <span class="text-6xl">♫</span>
        </div>
        <div class="flex flex-col gap-2">
            <span class="text-xs font-bold uppercase tracking-wider">Playlist</span>
            <h1 class="text-4xl font-black sm:text-7xl tracking-tighter">Mes Tracks</h1>
            <div class="mt-2 flex items-center gap-2 text-sm font-medium text-white/90">
                <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center text-black font-bold text-xs">
                    U
                </div>
                <span class="hover:underline cursor-pointer">Utilisateur</span>
                <span>•</span>
                <span>{{ trackCount }} titres</span>
                <span class="text-white/70">, env. {{ estimatedMinutes }} min</span>
            </div>
        </div>
    </header>
</template>
