// frontend/src/main.js
import './assets/main.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // ← Tu dois importer le routeur

createApp(App)
  .use(router) // ← Tu dois l’injecter ici
  .mount('#app');
  app.config.errorHandler = (err, vm, info) => {
    console.error("Vue error:", err);
    console.warn("Info:", info);
  };
 
