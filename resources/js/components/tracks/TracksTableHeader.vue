<script setup lang="ts">
import { Clock } from 'lucide-vue-next';

const props = defineProps<{
    isAdmin?: boolean;
}>();
</script>

<template>
    <div
        class="sticky top-0 z-20 grid grid-cols-[16px_4fr_3fr_2fr_minmax(70px,1fr)_minmax(90px,1fr)] items-center gap-4 border-b border-white/10 bg-[#121212] px-4 py-2 text-sm font-medium text-[#b3b3b3] uppercase tracking-wider"
    >
        <div class="text-center">#</div>
        <div>Titre</div>
        <div class="hidden sm:block">Album</div>
        <div class="hidden md:block">Date d'ajout</div>
        <div class="flex justify-end pr-4">
            <Clock :size="16" />
        </div>
        <div class="flex justify-end pr-2">
            {{ props.isAdmin ? 'Actions' : '' }}
        </div>
    </div>
</template>

