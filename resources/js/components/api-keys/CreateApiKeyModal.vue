<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    show: boolean;
}>();

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
});

const createApiKey = () => {
    form.post('/api-keys', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};
</script>

<template>
    <Dialog :open="show" @update:open="$emit('close')">
        <DialogContent class="sm:max-w-md bg-[#282828] border-none text-white">
            <DialogHeader>
                <DialogTitle>Créer une clé API</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="createApiKey" class="flex flex-col gap-4 mt-4">
                <div class="flex flex-col gap-2">
                    <Label for="name" class="text-gray-300">Nom</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Ma clé API"
                        class="bg-[#3e3e3e] border-none text-white placeholder:text-gray-400 focus-visible:ring-emerald-500"
                    />
                    <span v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</span>
                </div>
                <DialogFooter>
                    <Button type="button" variant="ghost" @click="$emit('close')" class="text-white hover:bg-white/10">Annuler</Button>
                    <Button type="submit" class="bg-[#1db954] text-black hover:bg-[#1ed760]" :disabled="form.processing">Créer</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
