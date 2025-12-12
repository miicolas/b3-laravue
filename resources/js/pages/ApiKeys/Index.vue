<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Trash2, Key, Copy, Check } from 'lucide-vue-next';
import CreateApiKeyModal from '@/components/api-keys/CreateApiKeyModal.vue';
import type { ApiKey } from '@/types/apiKey';
import { useClipboard } from '@vueuse/core';

const page = usePage();
const apiKeys = computed<ApiKey[]>(() => (page.props.apiKeys as ApiKey[]) ?? []);
const flash = computed(() => page.props.flash as { success?: string; created?: ApiKey; deleted?: any } | undefined);

const showCreateModal = ref(false);
const showSuccessModal = ref(false);
const newApiKey = ref<ApiKey | null>(null);

const { copy, copied } = useClipboard();

// Watch for flash message indicating a new key was created
watch(() => flash.value?.created, (createdKey) => {
    if (createdKey) {
        newApiKey.value = createdKey;
        showSuccessModal.value = true;
    }
}, { immediate: true });

const deleteKey = (apiKey: ApiKey) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette clé API ? Cette action est irréversible.')) {
        router.delete(`/api-keys/${apiKey.slug}`, {
            preserveScroll: true,
        });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const copyToClipboard = (text: string) => {
    copy(text);
};
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Clés API', href: '/api-keys' }]">
        <div class="min-h-screen bg-[#121212] text-white font-sans">
            <!-- Header Background -->
            <div class="absolute top-0 left-0 right-0 h-[340px] bg-gradient-to-b from-purple-900/50 to-[#121212] pointer-events-none"></div>

            <div class="relative z-10 px-6 pb-8 pt-6">
                <!-- Header Content -->
                <header class="flex flex-col gap-6 sm:flex-row sm:items-end">
                    <div class="h-[180px] w-[180px] shrink-0 bg-gradient-to-br from-purple-600 to-purple-900 shadow-2xl shadow-black/50 flex items-center justify-center rounded-md">
                        <Key class="text-white h-20 w-20" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-white">Développeur</span>
                        <h1 class="text-4xl font-black sm:text-7xl tracking-tighter text-white">Mes Clés API</h1>
                        <div class="mt-2 text-sm font-medium text-white/70">
                            Gérez vos clés d'accès à l'API. Gardez-les secrètes !
                        </div>
                    </div>
                </header>

                <!-- Action Bar -->
                <div class="mt-8 flex items-center gap-8 py-4">
                    <Button
                        class="rounded-full bg-[#1db954] text-black hover:bg-[#1ed760] font-bold px-8 h-12 text-base transition-transform hover:scale-105"
                        @click="showCreateModal = true"
                    >
                        Créer une clé
                    </Button>
                </div>

                <!-- Keys List -->
                <div class="mt-6">
                    <!-- Table Header -->
                    <div class="grid grid-cols-[50px_1fr_2fr_1fr_100px] gap-4 px-4 py-2 text-sm font-medium text-[#b3b3b3] border-b border-white/10 uppercase tracking-wider">
                        <div>#</div>
                        <div>Nom</div>
                        <div>Clé</div>
                        <div>Créée le</div>
                        <div class="text-right">Actions</div>
                    </div>

                    <!-- Table Body -->
                    <div class="mt-2 flex flex-col">
                        <div
                            v-for="(key, index) in apiKeys"
                            :key="key.id"
                            class="group grid grid-cols-[50px_1fr_2fr_1fr_100px] items-center gap-4 rounded-md px-4 py-3 text-sm text-[#b3b3b3] hover:bg-white/10 transition-colors"
                        >
                            <div class="text-white">{{ index + 1 }}</div>
                            <div class="font-medium text-white">{{ key.name || 'Sans nom' }}</div>
                            <div class="font-mono text-xs flex items-center gap-2">
                                <span class="truncate max-w-[200px]">{{ key.key }}</span>
                                <button @click="copyToClipboard(key.key)" class="opacity-0 group-hover:opacity-100 transition-opacity hover:text-white" title="Copier">
                                    <Check v-if="copied && newApiKey?.key !== key.key /* Hacky check, ideally track copied state per item */" class="h-3 w-3 text-green-500" />
                                    <Copy v-else class="h-3 w-3" />
                                </button>
                            </div>
                            <div>{{ formatDate(key.created_at) }}</div>
                            <div class="flex justify-end">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="text-[#b3b3b3] hover:text-red-500 hover:bg-transparent"
                                    @click="deleteKey(key)"
                                    title="Supprimer"
                                >
                                    <Trash2 class="h-5 w-5" />
                                </Button>
                            </div>
                        </div>

                        <div v-if="apiKeys.length === 0" class="flex flex-col items-center justify-center py-16 text-[#b3b3b3]">
                            <Key class="h-12 w-12 mb-4 opacity-50" />
                            <p>Vous n'avez pas encore de clé API.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <CreateApiKeyModal :show="showCreateModal" @close="showCreateModal = false" />

        <!-- Success Modal showing the new key -->
        <Dialog :open="showSuccessModal" @update:open="showSuccessModal = false">
            <DialogContent class="sm:max-w-lg bg-[#282828] border-none text-white">
                <DialogHeader>
                    <DialogTitle class="text-xl">Clé API créée !</DialogTitle>
                    <DialogDescription class="text-gray-400">
                        Voici votre nouvelle clé API. Copiez-la maintenant car elle ne sera peut-être plus visible en entier plus tard.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="mt-4 p-4 bg-[#121212] rounded border border-white/10 flex items-center gap-2">
                    <code class="flex-1 font-mono text-green-400 break-all">
                        {{ newApiKey?.key }}
                    </code>
                    <Button
                        size="icon"
                        variant="ghost"
                        class="shrink-0 text-white hover:bg-white/10"
                        @click="newApiKey?.key && copyToClipboard(newApiKey.key)"
                    >
                        <Check v-if="copied" class="h-4 w-4 text-green-500" />
                        <Copy v-else class="h-4 w-4" />
                    </Button>
                </div>

                <DialogFooter class="mt-6">
                    <Button class="bg-white text-black hover:bg-gray-200 font-bold rounded-full px-6" @click="showSuccessModal = false">
                        J'ai copié ma clé
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

    </AppLayout>
</template>
