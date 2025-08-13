<!-- frontend/src/views/ModifierHebergement.vue -->
<template>
    <div class="modifier-hebergement">
      <h2>Modifier l’hébergement</h2>
      <form @submit.prevent="submitModification">
        <input v-model="nom" type="text" placeholder="Nom du logement" required />
        <select v-model="type" required>
          <option>Hôtel</option>
          <option>Guesthouse</option>
          <option>Bungalow</option>
          <option>Appartement</option>
        </select>
        <textarea v-model="description" placeholder="Description" required></textarea>
        <input v-model.number="prix" type="number" placeholder="Prix par nuitée" required />
        <input v-model.number="adultes" type="number" placeholder="Capacité adultes" required />
        <input v-model.number="enfants" type="number" placeholder="Capacité enfants" required />
        <label>Services :</label>
        <div class="checkboxes">
          <label><input type="checkbox" value="Wi-Fi" v-model="services" /> Wi-Fi</label>
          <label><input type="checkbox" value="Petit déjeuner" v-model="services" /> Petit déjeuner</label>
          <label><input type="checkbox" value="Animaux autorisés" v-model="services" /> Animaux autorisés</label>
        </div>
        <button type="submit">Enregistrer</button>
        <p v-if="message">{{ message }}</p>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        nom: '',
        type: '',
        description: '',
        prix: '',
        adultes: '',
        enfants: '',
        services: [],
        message: ''
      };
    },
    mounted() {
      const id = this.$route.params.id;
      fetch(`http://localhost/soutenance_web/backend/api/get_hebergement.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
          this.nom = data.nom;
          this.type = data.type;
          this.description = data.description;
          this.prix = data.prixParNuit;
          this.adultes = data.capacite_adultes;
          this.enfants = data.capacite_enfants;
          this.services = data.services.split(',');
        });
    },
    methods: {
      async submitModification() {
        const formData = new FormData();
        formData.append("id", this.$route.params.id);
        formData.append("nom", this.nom);
        formData.append("type", this.type);
        formData.append("description", this.description);
        formData.append("prix", this.prix);
        formData.append("adultes", this.adultes);
        formData.append("enfants", this.enfants);
        formData.append("services", this.services.join(","));
  
        try {
          const res = await fetch("http://localhost/soutenance_web/backend/api/modifier_hebergement.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();
          if (data.success) {
            this.message = "Modification réussie !";
            this.$router.push("/mes-hebergements");
          } else {
            this.message = data.message || "Erreur lors de la modification.";
          }
        } catch (err) {
          this.message = "Erreur serveur.";
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .modifier-hebergement {
    padding: 20px;
    max-width: 600px;
    margin: auto;
  }
  form input, form select, form textarea {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
  }
  .checkboxes label {
    display: block;
    margin-bottom: 5px;
  }
  button {
    background-color: #ffc107;
    color: black;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }
  button:hover {
    background-color: #e0a800;
  }
  </style>
  