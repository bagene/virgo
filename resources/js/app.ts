import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import './bootstrap';
import App from './App.vue';
import routes from './router/index';
import { useAuth } from '@/composables/useAuth';

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

