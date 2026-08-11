import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import mitt from 'mitt';
import Swal from 'sweetalert2';
import VueApexCharts from 'vue3-apexcharts';
import Cookies from 'js-cookie';
import htmlToPaper from './plugins/htmlToPaper';

import { routes } from './routes';
import AppShell from './AppShell.vue';
import User from './helpers/User';
import Notification from './helpers/Notification';
import { useAuthStore } from './store/auth';

window.User = User;
window.Notification = Notification;
window.Swal = Swal;
window.Cookies = Cookies;

const bus = mitt();
window.Reload = {
    $on: (event, handler) => bus.on(event, handler),
    $off: (event, handler) => bus.off(event, handler),
    $emit: (event, payload) => bus.emit(event, payload),
};

window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

const pinia = createPinia();
const app = createApp(AppShell);
app.use(pinia);

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const authPages = new Set(['/', 'register', 'forget']);

router.beforeEach((to, from, next) => {
    const isAuthPage = authPages.has(to.name) || authPages.has(to.path);
    const auth = useAuthStore(pinia);
    if (!isAuthPage && !auth.isLoggedIn && !User.loggedIn()) {
        next({ name: '/' });
        return;
    }
    if ((to.name === 'users' || to.name === 'editUsers') && !auth.isAdmin) {
        next({ name: 'home' });
        return;
    }
    next();
});

app.use(router);
app.use(VueApexCharts);
app.use(htmlToPaper);
app.mount('#app');
