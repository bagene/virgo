<script setup lang="ts">
import { useToast } from '@/composables/useToast';

const { toasts, remove } = useToast();

const typeStyles: Record<string, string> = {
    success: 'bg-green-500',
    error: 'bg-red-500',
    info: 'bg-blue-500',
    warning: 'bg-amber-500',
};
</script>

<template>
    <div class="fixed top-4 right-4 z-50 space-y-3 w-80">
        <TransitionGroup name="toast" tag="div" class="space-y-3">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="rounded-lg shadow-lg text-white p-4 flex items-start gap-3"
                :class="typeStyles[toast.type]"
            >
                <div class="flex-1">
                    <p v-if="toast.title" class="font-semibold">{{ toast.title }}</p>
                    <p class="text-sm leading-snug">{{ toast.message }}</p>
                </div>
                <button
                    class="text-white/80 hover:text-white font-semibold"
                    aria-label="Dismiss"
                    @click="remove(toast.id)"
                >
                    ✕
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.2s ease;
}
.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>

