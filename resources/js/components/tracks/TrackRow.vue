<script setup lang="ts">
import { MoreHorizontal, Pause, Play } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import type { Track } from '@/types/track';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'

const props = withDefaults(defineProps<{
    track: Track;
    index: number;
    isCurrent: boolean;
    isPlaying: boolean;
    formattedDate: string;
    formattedDuration: string;
    isAdmin?: boolean;
    context?: 'library' | 'playlist';
}>(), {
    context: 'library',
});

const emit = defineEmits<{
    (e: 'play', id: number): void;
    (e: 'delete', slug: string): void;
    (e: 'add-to-playlist', track: Track): void;
    (e: 'remove-from-playlist', track: Track): void;
}>();

const handlePlay = () => emit('play', props.track.id);
const handleDelete = () => emit('delete', props.track.slug);
</script>

<template>
    <div
        class="group grid grid-cols-[16px_4fr_3fr_2fr_minmax(70px,1fr)_minmax(90px,1fr)] items-center gap-4 rounded-md px-4 py-2 text-sm text-[#b3b3b3] hover:bg-white/10 transition-colors"
        @dblclick="handlePlay"
    >
        <!-- ... (columns same) ... -->
        <!-- I need to be careful not to replace the whole template if I can't match it easily. -->
        <!-- Just replacing the script section and dropdown section? replace_file_content allows targeting chunks. -->
        <!-- I'll do partial replaces. -->

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
                v-if="track.thumbnail_url"
                :src="track.thumbnail_url"
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
        <div class="flex justify-end pr-2">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" class="text-white/70 hover:text-white">
                        <MoreHorizontal class="h-5 w-5" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>Actions</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    
                    <DropdownMenuItem @click="$emit('add-to-playlist', track)" class="cursor-pointer">
                        Ajouter à une playlist
                    </DropdownMenuItem>

                    <DropdownMenuItem 
                        v-if="context === 'playlist'" 
                        @click="$emit('remove-from-playlist', track)" 
                        class="text-red-500 cursor-pointer focus:text-red-500"
                    >
                        Retirer de la playlist
                    </DropdownMenuItem>

                    <template v-if="context === 'library' && isAdmin">
                        <DropdownMenuItem as-child>
                            <Link :href="`/tracks/${track.slug}/edit`" class="w-full cursor-pointer">
                                Modifier
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="handleDelete" class="text-red-500 focus:text-red-500">
                            Supprimer
                        </DropdownMenuItem>
                    </template>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </div>
</template>

