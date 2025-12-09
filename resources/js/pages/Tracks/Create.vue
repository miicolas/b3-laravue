<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const form = useForm({
    title: '',
    artist: '',
    album: '',
    genre: '',
    year: null as number | null,
    duration: '',
});

const submit = () => {
    form.post('/tracks');
};
</script>

<template>
    <div class="min-h-screen bg-[#121212] text-white">
        <!-- Header Gradient Background -->
        <div class="absolute top-0 left-0 right-0 h-[340px] bg-gradient-to-b from-emerald-800/80 to-[#121212] pointer-events-none"></div>

        <div class="relative z-10 px-6 pb-8 pt-6">
            <!-- Header -->
            <header class="flex flex-col gap-6">
                <Link href="/tracks" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors w-fit">
                    <ArrowLeft :size="20" />
                    <span>Retour aux tracks</span>
                </Link>

                <div class="flex flex-col gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Nouveau</span>
                    <h1 class="text-4xl font-black sm:text-7xl tracking-tighter">Créer un Track</h1>
                </div>
            </header>

            <!-- Form -->
            <div class="mt-12 max-w-2xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-2">
                        <Label for="title" class="text-white">Titre *</Label>
                        <Input
                            id="title"
                            v-model="form.title"
                            type="text"
                            placeholder="Entrez le titre du track"
                            required
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</p>
                    </div>

                    <!-- Artist -->
                    <div class="space-y-2">
                        <Label for="artist" class="text-white">Artiste *</Label>
                        <Input
                            id="artist"
                            v-model="form.artist"
                            type="text"
                            placeholder="Nom de l'artiste"
                            required
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.artist" class="text-red-500 text-sm">{{ form.errors.artist }}</p>
                    </div>

                    <!-- Album -->
                    <div class="space-y-2">
                        <Label for="album" class="text-white">Album</Label>
                        <Input
                            id="album"
                            v-model="form.album"
                            type="text"
                            placeholder="Nom de l'album"
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.album" class="text-red-500 text-sm">{{ form.errors.album }}</p>
                    </div>

                    <!-- Genre -->
                    <div class="space-y-2">
                        <Label for="genre" class="text-white">Genre</Label>
                        <Input
                            id="genre"
                            v-model="form.genre"
                            type="text"
                            placeholder="Genre musical"
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.genre" class="text-red-500 text-sm">{{ form.errors.genre }}</p>
                    </div>

                    <!-- Year -->
                    <div class="space-y-2">
                        <Label for="year" class="text-white">Année</Label>
                        <Input
                            id="year"
                            v-model.number="form.year"
                            type="number"
                            placeholder="Année de sortie"
                            min="1900"
                            :max="new Date().getFullYear()"
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.year" class="text-red-500 text-sm">{{ form.errors.year }}</p>
                    </div>

                    <!-- Duration -->
                    <div class="space-y-2">
                        <Label for="duration" class="text-white">Durée</Label>
                        <Input
                            id="duration"
                            v-model="form.duration"
                            type="text"
                            placeholder="ex: 3:45"
                            class="bg-[#282828] border-white/10 text-white placeholder:text-white/40"
                        />
                        <p v-if="form.errors.duration" class="text-red-500 text-sm">{{ form.errors.duration }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4">
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-[#1db954] hover:bg-[#1ed760] text-black font-semibold"
                        >
                            {{ form.processing ? 'Création...' : 'Créer le track' }}
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
</template>

<style scoped>
/* Custom Scrollbar if needed */
::-webkit-scrollbar {
    width: 12px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background-color: hsla(0,0%,100%,.3);
    border-radius: 20px;
    border: 3px solid transparent;
    background-clip: content-box;
}
::-webkit-scrollbar-thumb:hover {
    background-color: hsla(0,0%,100%,.5);
}
</style>
