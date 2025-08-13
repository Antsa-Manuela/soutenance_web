// frontend/src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import AjouterHebergement from '../views/AjouterHebergement.vue';
import ListeHebergements from '../views/ListeHebergements.vue';
import ModifierHebergement from '../views/ModifierHebergement.vue';

const routes = [
  { path: '/', redirect: '/login' }, // Login affiché en premier
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/ajouter-hebergement', component: AjouterHebergement },
  { path: '/mes-hebergements', component: ListeHebergements },
  { path: '/modifier-hebergement/:id', component: ModifierHebergement }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
