<!-- frontend/src/views/Evenements.vue -->
<template>
  <div class="evenements">
    <h2>Gestion des événements</h2>

    <form @submit.prevent="ajouter">
      <input v-model="titre" placeholder="Titre" required />
      <input v-model="dateDebut" type="date" required />
      <input v-model="dateFin" type="date" required />
      <textarea v-model="description" placeholder="Description courte"></textarea>
      <textarea v-model="resume" placeholder="Fiche résumé (détails)"></textarea>
      <input type="file" @change="handleImage" accept="image/*" />
      <button type="submit">Ajouter</button>
    </form>

    <hr />

    <div v-if="evenements.length === 0">Aucun événement.</div>
    <ul v-else>
      <li v-for="e in evenements" :key="e.id_event">
        <strong>{{ e.titre || 'Sans titre' }}</strong><br />
        📅 Du {{ e.date_debut || '...' }} au {{ e.date_fin || '...' }}<br />
        <em>{{ e.description || 'Pas de description' }}</em><br />
        <p>{{ e.resume || 'Pas de résumé' }}</p>
        <img
          v-if="e.image && typeof e.image === 'string'"
          :src="`http://localhost/soutenance_web/${e.image}`"
          alt="Illustration"
          class="illustration"
          @error="handleImageError"
        />
        <button @click="supprimer(e.id_event)">🗑️ Supprimer</button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      titre: '',
      dateDebut: '',
      dateFin: '',
      description: '',
      resume: '',
      imageFile: null,
      evenements: []
    };
  },
  mounted() {
    this.charger();
  },
  methods: {
    handleImage(e) {
      this.imageFile = e.target.files[0];
    },
    handleImageError(e) {
      e.target.style.display = "none";
    },
    async charger() {
      try {
        const res = await fetch("http://localhost/soutenance_web/backend/api/get_evenements.php");
        const data = await res.json();
        this.evenements = Array.isArray(data.evenements) ? data.evenements : [];
      } catch (err) {
        console.error("Erreur chargement événements :", err);
        this.evenements = [];
      }
    },
    async ajouter() {
      try {
        const formData = new FormData();
        formData.append("titre", this.titre);
        formData.append("date_debut", this.dateDebut);
        formData.append("date_fin", this.dateFin);
        formData.append("description", this.description);
        formData.append("resume", this.resume);
        formData.append("id_admin", localStorage.getItem("idAdmin"));
        if (this.imageFile) {
          formData.append("image", this.imageFile);
        }

        const res = await fetch("http://localhost/soutenance_web/backend/api/ajouter_evenement.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.titre = '';
        this.dateDebut = '';
        this.dateFin = '';
        this.description = '';
        this.resume = '';
        this.imageFile = null;
        this.charger();
      } catch (err) {
        console.error("Erreur ajout événement :", err);
        alert("Erreur lors de l’ajout.");
      };
      const res = await fetch("http://localhost/soutenance_web/backend/api/ajouter_evenement.php", {
        method: "POST",
        body: formData
      });
      const data = await res.json();
      console.log("Réponse ajout événement :", data); // ← Ajoute ça
      alert(data.message);
    },
    async supprimer(id) {
      try {
        const formData = new FormData();
        formData.append("id_event", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/supprimer_evenement.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      } catch (err) {
        console.error("Erreur suppression événement :", err);
        alert("Erreur lors de la suppression.");
      }
    }
  }
};
</script>

<style scoped>
.evenements {
  padding: 20px;
  max-width: 700px;
  margin: auto;
  color: black;
}
form input, form textarea {
  display: block;
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
}
button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
}
button:hover {
  background-color: #0056b3;
}
li {
  margin-bottom: 25px;
}
.illustration {
  max-width: 100%;
  max-height: 200px;
  margin-top: 10px;
  border-radius: 6px;
}
</style>
