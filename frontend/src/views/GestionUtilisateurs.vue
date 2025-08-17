<!-- frontend/src/views/GestionUtilisateurs.vue -->
<template>
    <div class="gestion-utilisateurs">
      <h2>Utilisateurs</h2>
  
      <select v-model="filtreRole" @change="charger">
        <option value="">Tous</option>
        <option value="admin">Administrateurs</option>
        <option value="hote">Hôtes</option>
        <option value="client">Clients</option>
      </select>
  
      <div v-if="utilisateurs.length === 0">Aucun utilisateur trouvé.</div>
      <ul v-else>
        <li v-for="u in utilisateurs" :key="u.id">
          <strong>{{ u.nomComplet }}</strong> – {{ u.email }} ({{ u.role }})<br />
          <img v-if="u.photoProfil" :src="`http://localhost/soutenance_web/${u.photoProfil}`" alt="Profil" class="profil" />
          <button @click="supprimer(u.id)">🗑️ Supprimer</button>
          <button v-if="u.role === 'hote'" @click="promouvoir(u.id)">⚡ Promouvoir en admin</button>
          <button v-if="u.role === 'admin'" @click="retrograder(u.id)">↩️ Rétrograder en hôte</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        utilisateurs: [],
        filtreRole: ''
      };
    },
    mounted() {
      this.charger();
    },
    methods: {
      async charger() {
        try {
          const url = this.filtreRole
            ? `http://localhost/soutenance_web/backend/api/get_utilisateurs.php?role=${this.filtreRole}`
            : `http://localhost/soutenance_web/backend/api/get_utilisateurs.php`;
  
          const res = await fetch(url);
          const data = await res.json();
          this.utilisateurs = data.utilisateurs || [];
        } catch (err) {
          console.error("Erreur chargement utilisateurs :", err);
        }
      },
      async supprimer(id) {
        if (!confirm("Supprimer cet utilisateur ?")) return;
        const formData = new FormData();
        formData.append("id", id);
  
        const res = await fetch("http://localhost/soutenance_web/backend/api/supprimer_utilisateur.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      },
      async promouvoir(id) {
        const formData = new FormData();
        formData.append("id", id);
  
        const res = await fetch("http://localhost/soutenance_web/backend/api/promouvoir_admin.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      },
      async retrograder(id) {
        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/retrograder_admin.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      }
    }
  };
  </script>
  
  <style scoped>
  .gestion-utilisateurs {
    padding: 20px;
    max-width: 700px;
    margin: auto;
    color: black;
  }
  .profil {
    max-width: 80px;
    border-radius: 50%;
    margin-top: 5px;
  }
  button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    margin-top: 5px;
    margin-right: 5px;
  }
  select {
    margin-bottom: 15px;
    padding: 6px;
  }
  </style>
  