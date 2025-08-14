<!-- frontend/src/views/Statistiques.vue -->
<template>
    <div class="statistiques">
      <h2>Statistiques de l’établissement</h2>
      <div v-if="error">{{ error }}</div>
      <div v-else>
        <p>👁️ Nombre de vues : {{ stats.vues }}</p>
        <p>✅ Réservations confirmées : {{ stats.confirmées }}</p>
        <p>❌ Réservations annulées : {{ stats.annulées }}</p>
        <p>📈 Taux d’occupation : {{ stats.occupation }}%</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        stats: {},
        error: ''
      };
    },
    mounted() {
      const idHotel = localStorage.getItem("idHotel");
      if (!idHotel) {
        this.error = "Hôtel non identifié.";
        return;
      }
  
      fetch(`http://localhost/soutenance_web/backend/api/get_statistiques.php?id_hotel=${idHotel}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) {
            this.error = data.error;
          } else {
            this.stats = data;
          }
        })
        .catch(err => {
          this.error = "Erreur serveur.";
        });
    }
  };
  </script>
  
  <style scoped>
  .statistiques {
    padding: 20px;
    font-family: Arial, sans-serif;
    color: black;
  }
  p {
    margin-bottom: 10px;
    font-size: 16px;
  }
  </style>
  