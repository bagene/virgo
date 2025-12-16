<script setup lang="ts">
import {useAuth} from "@/composables/useAuth.ts";

const { isAuthenticated, logout } = useAuth();
</script>
<template>
    <div id="app">
        <nav v-if="isAuthenticated" class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <router-link
                                to="/"
                                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                active-class="border-indigo-500 text-gray-900"
                            >
                                Profile
                            </router-link>
                            <router-link
                                to="/order"
                                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                active-class="border-indigo-500 text-gray-900"
                            >
                                Limit Order
                            </router-link>
                            <button
                                @click="logout"
                                class="cursor-pointer border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <router-view />
        </main>
    </div>
</template>

