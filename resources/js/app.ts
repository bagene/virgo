import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import './bootstrap';
import App from './App.vue';
import routes from './router/index';
import { useAuth } from '@/composables/useAuth';
import {configureEcho} from "@laravel/echo-vue";
import axios from "axios";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, _from, next) => {
    const { isAuthenticated, token, fetchUser } = useAuth();
    const requiresAuth = Boolean(to.meta?.requiresAuth);
    const guestOnly = Boolean(to.meta?.guestOnly);

    // If we have a token but no user loaded yet, try to fetch it
    if (token.value && !isAuthenticated.value) {
        await fetchUser();
    }

    if (requiresAuth && !isAuthenticated.value) {
        return next({ name: 'Login', query: { redirect: to.fullPath } });
    }

    if (guestOnly && isAuthenticated.value) {
        return next({ name: 'Profile' });
    }

    return next();
});

const app = createApp(App);

app.use(router);
app.mount('#app');
configureEcho({
    broadcaster: 'reverb',
    authEndpoint: '/api/broadcasting/auth',
    key: import.meta.env.VITE_REVERB_APP_KEY,
wsHost: import.meta.env.VITE_REVERB_HOST,
wsPort: import.meta.env.VITE_REVERB_PORT,
wssPort: import.meta.env.VITE_REVERB_PORT,
forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
        return {
            authorize: async (socketId, callback) => {
                axios.post('/api/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name,
                }).then(response => {
                    callback(false, response.data);
                }).catch(error => {
                    callback(true, error);
                });
            }
        };
    }
});
