// frontend/src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import AjouterHebergement from '../views/AjouterHebergement.vue';
import ListeHebergements from '../views/ListeHebergements.vue';
import ModifierHebergement from '../views/ModifierHebergement.vue';
import ListeReservations from '../views/ListeReservations.vue';
import Statistiques from '../views/Statistiques.vue';
import Messagerie from '../views/Messagerie.vue';
import Profil from '../views/Profil.vue';
import Evenements from '../views/Evenements.vue';
import AdminLogin from '../views/AdminLogin.vue';
import AdminDashboard from '../views/AdminDashboard.vue';

const routes = [
  { path: '/', redirect: '/login' }, // Login affiché en premier
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/ajouter-hebergement', component: AjouterHebergement },
  { path: '/mes-hebergements', component: ListeHebergements },
  { path: '/modifier-hebergement/:id', component: ModifierHebergement },
  { path: '/reservations', component: ListeReservations },
  { path: '/statistiques', component: Statistiques },
  { path: '/messagerie', component: Messagerie },
  { path: '/profil', component: Profil },
  { path: '/evenements', component: Evenements },
  { path: '/admin-login', component: AdminLogin },
  { path: '/admin-dashboard', component: AdminDashboard },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
