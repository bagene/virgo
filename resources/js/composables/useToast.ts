import { computed, ref } from 'vue';

type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface Toast {
    id: number;
    title?: string;
    message: string;
    type: ToastType;
    duration: number;
}

const toasts = ref<Toast[]>([]);

const DEFAULT_DURATION = 4000;

export function useToast() {
    const add = (payload: Partial<Omit<Toast, 'id'>> & { message: string }) => {
        const toast: Toast = {
            id: Date.now() + Math.floor(Math.random() * 1000),
            title: payload.title,
            message: payload.message,
            type: payload.type ?? 'info',
            duration: payload.duration ?? DEFAULT_DURATION,
        };

        toasts.value.push(toast);

        // auto-remove after duration
        setTimeout(() => {
            remove(toast.id);
        }, toast.duration);
    };

    const remove = (id: number) => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    };

    return {
        toasts: computed(() => toasts.value),
        add,
        remove,
    };
}

