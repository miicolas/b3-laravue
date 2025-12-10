<script setup lang="ts">
import { useForm, Link, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Upload, Music, Image as ImageIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import type { Track } from '@/types/track';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const page = usePage();
const track = computed<Track>(() => page.props.track as Track);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Mes Tracks', href: '/tracks' },
    { title: 'Modifier', href: `/tracks/${track.value.id}/edit` },
];

const form = useForm({
    title: track.value.title ?? '',
    artist: track.value.artist ?? '',
    album: track.value.album ?? '',
    genre: track.value.genre ?? '',
    year: track.value.year ?? '',
    duration: track.value.duration ?? '',
    is_visible: track.value.is_visible ?? true,
    thumbnail: null as File | null,
    audio_file: null as File | null,
});

const thumbnailPreview = ref<string | null>(track.value.thumbnail_url ?? null);
const audioFileName = ref<string | null>(track.value.audio_file_url ? 'Fichier audio actuel' : null);

const handleThumbnailChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] ?? null;
    form.thumbnail = file;

    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const handleAudioChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] ?? null;
    form.audio_file = file;
    audioFileName.value = file?.name ?? null;
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(`/tracks/${track.value.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen bg-[#121212] text-white">
            <div class="absolute top-0 left-0 right-0 h-[340px] bg-gradient-to-b from-amber-800/80 to-[#121212] pointer-events-none"></div>

            <div class="relative z-10 px-6 pb-8 pt-6">
                <header class="flex flex-col gap-6">
                    <Link href="/tracks" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors w-fit">
                        <ArrowLeft :size="20" />
                        <span>Retour aux tracks</span>
                    </Link>

                    <div class="flex items-end gap-6">
                        <!-- Thumbnail Preview -->
                        <div class="h-[180px] w-[180px] shrink-0 bg-gradient-to-br from-amber-600 to-amber-900 shadow-2xl shadow-black/50 flex items-center justify-center rounded-md overflow-hidden">
                            <img
                                v-if="thumbnailPreview"
                                :src="thumbnailPreview"
                                alt="Thumbnail"
                                class="w-full h-full object-cover"
                            />
                            <Music v-else :size="48" class="text-white/60" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider">Modifier</span>
                            <h1 class="text-4xl font-black sm:text-6xl tracking-tighter">{{ form.title || 'Sans titre' }}</h1>
                            <p class="text-white/70">{{ form.artist || 'Artiste inconnu' }}</p>
                        </div>
                    </div>
                </header>

                <!-- Form -->
                <div class="mt-12 max-w-2xl">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title & Artist Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="title" class="text-white">Titre *</Label>
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Entrez le titre du track"
                                    required
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="artist" class="text-white">Artiste *</Label>
                                <Input
                                    id="artist"
                                    v-model="form.artist"
                                    type="text"
                                    placeholder="Nom de l'artiste"
                                    required
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.artist" class="text-red-500 text-sm">{{ form.errors.artist }}</p>
                            </div>
                        </div>

                        <!-- Album & Genre Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="album" class="text-white">Album</Label>
                                <Input
                                    id="album"
                                    v-model="form.album"
                                    type="text"
                                    placeholder="Nom de l'album"
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.album" class="text-red-500 text-sm">{{ form.errors.album }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="genre" class="text-white">Genre</Label>
                                <Input
                                    id="genre"
                                    v-model="form.genre"
                                    type="text"
                                    placeholder="Genre musical"
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.genre" class="text-red-500 text-sm">{{ form.errors.genre }}</p>
                            </div>
                        </div>

                        <!-- Year & Duration Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="year" class="text-white">Année</Label>
                                <Input
                                    id="year"
                                    v-model="form.year"
                                    type="number"
                                    placeholder="Année de sortie"
                                    min="1900"
                                    :max="new Date().getFullYear()"
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.year" class="text-red-500 text-sm">{{ form.errors.year }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="duration" class="text-white">Durée</Label>
                                <Input
                                    id="duration"
                                    v-model="form.duration"
                                    type="text"
                                    placeholder="ex: 3:45"
                                    class="bg-[#282828] border-white/10 text-white placeholder:text-white/40 focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p v-if="form.errors.duration" class="text-red-500 text-sm">{{ form.errors.duration }}</p>
                            </div>
                        </div>

                        <!-- File Uploads -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Thumbnail -->
                            <div class="space-y-2">
                                <Label for="thumbnail" class="text-white">Thumbnail</Label>
                                <label
                                    for="thumbnail"
                                    class="flex flex-col items-center justify-center w-full h-32 bg-[#282828] border-2 border-dashed border-white/20 rounded-lg cursor-pointer hover:border-amber-500/50 hover:bg-[#333] transition-colors"
                                >
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <ImageIcon :size="24" class="mb-2 text-white/40" />
                                        <p class="text-sm text-white/60">
                                            <span class="font-semibold text-amber-400">Cliquez</span> ou glissez
                                        </p>
                                        <p class="text-xs text-white/40">PNG, JPG (max 2MB)</p>
                                    </div>
                                    <input
                                        id="thumbnail"
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        @change="handleThumbnailChange"
                                    />
                                </label>
                                <p v-if="thumbnailPreview && !form.thumbnail" class="text-xs text-white/50">Image actuelle conservée</p>
                                <p v-if="form.errors.thumbnail" class="text-red-500 text-sm">{{ form.errors.thumbnail }}</p>
                            </div>

                            <!-- Audio File -->
                            <div class="space-y-2">
                                <Label for="audio_file" class="text-white">Fichier Audio</Label>
                                <label
                                    for="audio_file"
                                    class="flex flex-col items-center justify-center w-full h-32 bg-[#282828] border-2 border-dashed border-white/20 rounded-lg cursor-pointer hover:border-amber-500/50 hover:bg-[#333] transition-colors"
                                >
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <Upload :size="24" class="mb-2 text-white/40" />
                                        <p class="text-sm text-white/60">
                                            <span class="font-semibold text-amber-400">Cliquez</span> ou glissez
                                        </p>
                                        <p class="text-xs text-white/40">MP3, WAV (max 15MB)</p>
                                    </div>
                                    <input
                                        id="audio_file"
                                        type="file"
                                        accept="audio/*"
                                        class="hidden"
                                        @change="handleAudioChange"
                                    />
                                </label>
                                <p v-if="audioFileName" class="text-xs text-white/50 truncate">{{ audioFileName }}</p>
                                <p v-if="form.errors.audio_file" class="text-red-500 text-sm">{{ form.errors.audio_file }}</p>
                            </div>
                        </div>

                        <!-- Visibility Toggle -->
                        <div class="flex items-center space-x-3 p-4 bg-[#282828] rounded-lg">
                            <Checkbox
                                id="is_visible"
                                :checked="form.is_visible"
                                @update:checked="form.is_visible = $event"
                            />
                            <div>
                                <Label for="is_visible" class="text-white cursor-pointer">Visible publiquement</Label>
                                <p class="text-xs text-white/50">Ce track sera visible dans la liste publique</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-amber-500 hover:bg-amber-400 text-black font-semibold"
                            >
                                {{ form.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                            </Button>
                            <Link href="/tracks">
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="border-white/10 text-white hover:bg-white/10"
                                >
                                    Annuler
                                </Button>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
