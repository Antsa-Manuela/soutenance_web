<!-- frontend/src/views/AjouterHebergement.vue -->
<template>
    <div class="ajouter-hebergement">
      <h2>Ajouter un hébergement</h2>
      <form @submit.prevent="submitHebergement">
        <input v-model="nom" type="text" placeholder="Nom du logement" required />
        
        <select v-model="type" required>
          <option disabled value="">Type d’hébergement</option>
          <option>Hôtel</option>
          <option>Guesthouse</option>
          <option>Bungalow</option>
          <option>Appartement</option>
        </select>
  
        <textarea v-model="description" placeholder="Description" required></textarea>
  
        <input v-model.number="prix" type="number" placeholder="Prix par nuitée" required />
        <input v-model.number="adultes" type="number" placeholder="Capacité adultes" required />
        <input v-model.number="enfants" type="number" placeholder="Capacité enfants" required />
  
        <label>Services proposés :</label>
        <div class="checkboxes">
          <label><input type="checkbox" value="Wi-Fi" v-model="services" /> Wi-Fi</label>
          <label><input type="checkbox" value="Petit déjeuner" v-model="services" /> Petit déjeuner</label>
          <label><input type="checkbox" value="Animaux autorisés" v-model="services" /> Animaux autorisés</label>
        </div>
  
        <button type="submit">Ajouter</button>
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
    methods: {
      async submitHebergement() {
        const idHote = localStorage.getItem("idHote");
        if (!idHote) {
          this.message = "Hôte non connecté.";
          return;
        }
  
        const formData = new FormData();
        formData.append("idHote", idHote);
        formData.append("nom", this.nom);
        formData.append("type", this.type);
        formData.append("description", this.description);
        formData.append("prix", this.prix);
        formData.append("adultes", this.adultes);
        formData.append("enfants", this.enfants);
        formData.append("services", this.services.join(","));
  
        try {
          const res = await fetch("http://localhost/soutenance_web/backend/api/ajouter_hebergement.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();
          if (data.success) {
            this.message = "Hébergement ajouté avec succès !";
            this.$router.push("/dashboard");
          } else {
            this.message = data.message || "Erreur lors de l’ajout.";
          }
        } catch (err) {
          this.message = "Erreur serveur.";
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .ajouter-hebergement {
    padding: 20px;
    max-width: 600px;
    margin: auto;
    color: black;
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
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }
  button:hover {
    background-color: #218838;
  }
  </style>
  