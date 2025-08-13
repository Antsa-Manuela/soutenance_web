<!-- frontend/src/views/Dashboard.vue -->
<template>
  <div class="dashboard">
    <h2>Bienvenue, {{ nomEtablissement }}</h2>

    <!-- Résumé rapide -->
    <section class="resume">
      <h3>Résumé rapide</h3>
      <div class="stats">
        <div class="stat-box">📅 Réservations en cours : {{ stats.reservations }}</div>
        <div class="stat-box">👁️ Nombre de vues : {{ stats.vues }}</div>
        <div class="stat-box">💬 Nouveaux messages : {{ stats.messages }}</div>
      </div>
    </section>

    <!-- Boutons d'accès rapide -->
    <section class="actions">
      <h3>Actions rapides</h3>
      <div class="buttons">
        <router-link to="/ajouter-hebergement" class="btn">➕ Ajouter un hébergement</router-link>
        <router-link to="/reservations" class="btn">📋 Gérer les réservations</router-link>
        <router-link to="/statistiques" class="btn">📊 Voir statistiques</router-link>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      nomEtablissement: localStorage.getItem("nomEtablissement") || "Nom de l’établissement",
      stats: {
        reservations: 0,
        vues: 0,
        messages: 0,
      },
    };
  },
  mounted() {
    const idHote = localStorage.getItem("idHote");
    if (!idHote) return;

    fetch(`http://localhost/soutenance_web/backend/api/get_dashboard_stats.php?id_hote=${idHote}`)
      .then(res => res.json())
      .then(data => {
        if (!data.error) {
          this.stats.reservations = data.reservations;
          this.stats.vues = data.vues;
          this.stats.messages = data.messages;
        } else {
          console.error("Erreur API:", data.error);
        }
      })
      .catch(err => {
        console.error("Erreur fetch:", err);
      });
  }
};
</script>
  
  <style scoped>
  .dashboard {
    padding: 20px;
    font-family: Arial, sans-serif;
    color: black;
  }
  .resume, .actions {
    margin-bottom: 30px;
  }
  .stats {
    display: flex;
    gap: 20px;
  }
  .stat-box {
    background: #f0f0f0;
    padding: 15px;
    border-radius: 8px;
    flex: 1;
    text-align: center;
  }
  .buttons {
    display: flex;
    gap: 20px;
  }
  .btn {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    text-decoration: none;
  }
  .btn:hover {
    background-color: #0056b3;
  }
  </style>
  