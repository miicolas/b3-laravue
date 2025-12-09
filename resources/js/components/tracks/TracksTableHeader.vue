<script setup lang="ts">
import { Clock } from 'lucide-vue-next';
</script>

<template>
    <div
        class="sticky top-0 z-20 grid grid-cols-[16px_4fr_3fr_2fr_minmax(60px,1fr)] gap-4 border-b border-white/10 bg-[#121212] px-4 py-2 text-sm font-medium text-[#b3b3b3] uppercase tracking-wider"
    >
        <div class="text-center">#</div>
        <div>Titre</div>
        <div class="hidden sm:block">Album</div>
        <div class="hidden md:block">Date d'ajout</div>
        <div class="flex justify-end pr-8">
            <Clock :size="16" />
        </div>
    </div>
</template>

