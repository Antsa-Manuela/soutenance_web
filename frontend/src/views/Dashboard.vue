<template>
  <div class="dashboard">
    <h2>Bienvenue, {{ nomEtablissement }}</h2>

    <!-- Résumé rapide -->
    <section class="resume">
      <h3>Résumé rapide</h3>
      <div class="stats">
        <router-link to="/reservations" class="stat-box clickable">
          📅 Réservations en cours : {{ stats.reservations }}
        </router-link>
        <router-link to="/statistiques" class="stat-box clickable">
          👁️ Nombre de vues : {{ stats.vues }}
        </router-link>
        <router-link
          v-if="idClient"
          :to="`/messagerie?idClient=${idClient}`"
          class="stat-box clickable"
        >
          💬 Messages : {{ stats.messages }} nouveaux / {{ stats.totalMessages }} total
        </router-link>
        <router-link
          v-else
          to="/messagerie"
          class="stat-box clickable"
        >
          💬 Aucun message client pour l’instant
        </router-link>
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
    <button @click="deconnexion">Déconnexion</button>
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
        totalMessages: 0
      },
      idClient: null
    };
  },
  mounted() {
    const idHote = localStorage.getItem("idHote");
    console.log("ID Hôte récupéré :", idHote);
    if (!idHote) return;

    // Récupérer les stats
    fetch(`http://localhost/soutenance_web/backend/api/get_dashboard_stats.php?id_hote=${idHote}`)
      .then(res => res.json())
      .then(data => {
        if (!data.error) {
          this.stats.reservations = data.reservations;
          this.stats.vues = data.vues;
          this.stats.messages = data.messages;
          this.stats.totalMessages = data.totalMessages;
        }
      });

    // Récupérer le dernier client lié à cet hôte
    fetch(`http://localhost/soutenance_web/backend/api/get_last_client.php?id_hote=${idHote}`)
      .then(res => res.json())
      .then(data => {
        // Juste après avoir récupéré idClient
        if (data.idClient) {
          this.idClient = data.idClient;
          localStorage.setItem("idClient", data.idClient); // ← stocker pour la messagerie
        }
      });
  },
  methods: {
  deconnexion() {
    localStorage.clear();
    this.$router.push("/login");
  }
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
.stat-box.clickable {
  cursor: pointer;
  transition: background-color 0.3s;
  text-decoration: none;
  color: inherit;
}
.stat-box.clickable:hover {
  background-color: #e0e0e0;
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
