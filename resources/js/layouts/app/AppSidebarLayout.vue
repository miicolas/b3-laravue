<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import PlayerBar from '@/components/PlayerBar.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePlayer } from '@/composables/usePlayer';

const { currentTrack } = usePlayer();

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden" :class="{ 'pb-[90px]': currentTrack }">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
        <PlayerBar v-if="currentTrack" />
    </AppShell>
</template>
