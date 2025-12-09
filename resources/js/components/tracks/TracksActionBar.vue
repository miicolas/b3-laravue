<script setup lang="ts">
import { Heart, MoreHorizontal, Play, Plus } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const emit = defineEmits<{
    (e: 'play'): void;
}>();
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
        <Link href="/tracks/create">
            <Button
                variant="outline"
                type="button"
                class="flex items-center gap-2 border-white/10 text-white hover:bg-white/10"
            >
                <Plus :size="20" />
                <span>Nouveau track</span>
            </Button>
        </Link>
        <Dialog>
            <DialogTrigger as-child>
                <Button variant="outline" type="button">
                    <MoreHorizontal :size="24" />
                </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-[425px]">
                <form class="grid gap-4">
                    <DialogHeader>
                        <DialogTitle>Edit profile</DialogTitle>
                        <DialogDescription>
                            Make changes to your profile here. Click save when you're done.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="grid gap-4">
                        <div class="grid gap-3">
                            <Label for="name-1">Name</Label>
                            <Input id="name-1" name="name" default-value="Pedro Duarte" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="username-1">Username</Label>
                            <Input id="username-1" name="username" default-value="@peduarte" />
                        </div>
                    </div>
                    <DialogFooter>
                        <DialogClose as-child>
                            <Button variant="outline" type="button">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit">
                            Save changes
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
