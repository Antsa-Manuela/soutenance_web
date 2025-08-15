<!-- frontend/src/views/ListeHebergements.vue -->
<template>
  <div class="liste-hebergements">
    <h2>Mes hébergements</h2>
    <div v-if="hebergements.length === 0">Aucun hébergement trouvé.</div>
    <table v-else>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Type</th>
          <th>Prix</th>
          <th>Capacité</th>
          <th>Services</th>
          <th>Photo</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="h in hebergements" :key="h.id_hebergement">
          <td>{{ h.nom }}</td>
          <td>{{ h.type }}</td>
          <td>{{ h.prixParNuit }} Ar</td>
          <td>{{ h.capacite_adultes }}A / {{ h.capacite_enfants }}E</td>
          <td>{{ h.services }}</td>
          <td>
            <img
              v-if="h.photos && h.photos.length"
              :src="h.photos[0]"
              alt="Photo"
              style="max-width: 100px; border-radius: 6px;"
            />
          </td>
          <td>
            <button @click="supprimer(h.id_hebergement)">🗑️</button>
            <router-link :to="`/modifier-hebergement/${h.id_hebergement}`">✏️</router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      hebergements: []
    };
  },
  mounted() {
    const idHote = localStorage.getItem("idHote");
    if (!idHote) return;

    fetch(`http://localhost/soutenance_web/backend/api/get_hebergements.php?id_hote=${idHote}`)
      .then(res => res.json())
      .then(data => {
        this.hebergements = data.hebergements || [];
      });
  },
  methods: {
    async supprimer(id) {
      if (!confirm("Confirmer la suppression ?")) return;

      const formData = new FormData();
      formData.append("id", id);

      try {
        const res = await fetch("http://localhost/soutenance_web/backend/api/supprimer_hebergement.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        if (data.success) {
          this.hebergements = this.hebergements.filter(h => h.id_hebergement !== id);
        } else {
          alert(data.message || "Erreur lors de la suppression.");
        }
      } catch (err) {
        alert("Erreur serveur.");
      }
    }
  }
};
</script>

<style scoped>
.liste-hebergements {
  padding: 20px;
  color: black;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: left;
}
thead {
  background-color: #f0f0f0;
}
img {
  max-height: 80px;
  object-fit: cover;
}
</style>
