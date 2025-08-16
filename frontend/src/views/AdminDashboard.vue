<!-- frontend/src/views/AdminDashboard.vue -->
<template>
    <div class="admin-dashboard">
      <h2>Bienvenue, {{ nomAdmin }}</h2>
      <router-link to="/gestion-utilisateurs">👥 Utilisateurs</router-link><br />
      <router-link to="/evenements">📅 Événements</router-link><br />
      <button @click="logout">Déconnexion</button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        nomAdmin: localStorage.getItem("nomAdmin") || "Admin"
      };
    },
    methods: {
      logout() {
        localStorage.removeItem("idAdmin");
        localStorage.removeItem("nomAdmin");
        this.$router.push("/admin-login");
      }
    }
  };
  </script>
  
  <style scoped>
  .admin-dashboard {
    padding: 20px;
    text-align: center;
  }
  button {
    margin-top: 20px;
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
  }
  </style>
  