import { RouteRecordRaw } from 'vue-router';
import Login from '@/views/Login.vue';
import Profile from '@/views/Profile.vue';
import Order from '@/views/Order.vue';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'Profile',
        component: Profile,
        meta: { requiresAuth: true },
    },
    {
        path: '/order',
        name: 'Order',
        component: Order,
        meta: { requiresAuth: true },
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { guestOnly: true },
    }
];

export default routes;

