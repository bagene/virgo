<script setup lang="ts">

import {useAuth} from "@/composables/useAuth.ts";
import {reactive, watch} from "vue";

const form = reactive({
    email: '',
    password: '',
});

const { login, isAuthenticated } = useAuth();

const submit = async () => {
    await login(form);

    if (isAuthenticated.value) {
        window.location.href = '/';
    }
}

watch(isAuthenticated, (newValue) => {
    if (newValue) {
        console.log('User is authenticated, redirecting to Home');
        window.location.href = '/';
    }
});
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        id="password"
                        class="w-full px-3 py-2 border rounded"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition duration-200"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</template>
