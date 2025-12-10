<script setup lang="ts">
import { Heart, Play, Plus, Search } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { ref, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

const props = defineProps<{
    search?: string;
    isAdmin?: boolean;
}>();

const emit = defineEmits<{
    (e: 'play'): void;
}>();

const searchQuery = ref(props.search ?? '');

const performSearch = useDebounceFn((value: string) => {
    router.get('/tracks', { search: value || undefined }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}, 300);

watch(searchQuery, (value) => {
    performSearch(value);
});
</script>

<template>
    <div class="mt-6 flex items-center gap-8 py-4">
        <button
            class="flex h-14 w-14 items-center justify-center rounded-full bg-[#1db954] text-black shadow-lg transition hover:scale-105 hover:bg-[#1ed760] active:scale-100"
            @click="emit('play')"
        >
            <Play :size="28" class="fill-current ml-1" />
        </button>
        <button class="text-white/70 hover:text-white">
            <Heart :size="32" />
        </button>
        <Link v-if="props.isAdmin" href="/tracks/create">
            <Button
                variant="outline"
                type="button"
                class="flex items-center gap-2 border-white/10 text-white hover:bg-white/10"
            >
                <Plus :size="20" />
                <span>Nouveau track</span>
            </Button>
        </Link>

        <!-- Search Input -->
        <div class="relative ml-auto">
            <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-white/50" />
            <Input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher un titre..."
                class="w-64 bg-white/10 border-white/10 text-white placeholder:text-white/50 pl-10 focus:bg-white/20 focus:border-white/20"
            />
        </div>
    </div>
</template>
