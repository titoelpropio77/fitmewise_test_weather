
import { createApp } from 'vue';
import App from '../pages/App.vue';
import '../css/app.css';

const app = createApp(App);

import { createPinia } from 'pinia';
const pinia = createPinia();
app.use(pinia);

app.mount('#app');

