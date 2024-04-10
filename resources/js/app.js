import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-browser.js';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';

import Test from './components/Test.vue';

const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);

app.component('test-component', Test);

app.mount('#app');