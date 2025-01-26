import { createRouter, createWebHistory } from 'vue-router';
import type { RouteRecordRaw } from 'vue-router';

import Login from './../pages/auth/login.vue';
import Register from './../pages/auth/register.vue';
import Home from './../pages/dashboard/home.vue';
import Travel from './../pages/dashboard/travel.vue';
import Booking from "../pages/dashboard/booking.vue";
import Profile from "../pages/dashboard/profile.vue";

const routes: Array<RouteRecordRaw> = [
    { path: '/', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/home', name: 'Home', component: Home },
    { path: '/travel', name: 'Travel', component: Travel },
    { path: '/booking', name: 'Booking', component: Booking },
    { path: '/profile', name: 'Profile', component: Profile },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;