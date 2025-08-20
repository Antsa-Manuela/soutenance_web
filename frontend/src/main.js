/* frontend/src/main.js */
import './assets/main.css';
import './assets/theme.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

const app = createApp(App); // ← stocker l'app
app.use(router);
app.config.errorHandler = (err, vm, info) => {
  console.error("Vue error:", err);
  console.warn("Info:", info);
};
app.mount('#app');
