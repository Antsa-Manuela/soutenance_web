<!-- frontend/src/views/Register.vue -->
<template>
    <div class="register">
      <h2>Créer un compte hôte</h2>
      <form @submit.prevent="submitForm">
        <input v-model="nomEtablissement" placeholder="Nom de l’établissement" required />
        <input v-model="email" type="email" placeholder="Email" required />
        <input v-model="telephone" placeholder="Téléphone" required />
        <input v-model="motDePasse" type="password" placeholder="Mot de passe" required />
        <input v-model="confirmation" type="password" placeholder="Confirmer le mot de passe" required />
        <input v-model="adresse" placeholder="Adresse complète" required />
        <select v-model="typeHebergement">
          <option disabled value="">Type d’hébergement</option>
          <option>Hôtel</option>
          <option>Guesthouse</option>
          <option>Bungalow</option>
        </select>
        <input type="file" @change="handleFile" />
        <button type="submit">Créer le compte</button>
      </form>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        nomEtablissement: '',
        email: '',
        telephone: '',
        motDePasse: '',
        confirmation: '',
        adresse: '',
        typeHebergement: '',
        justificatif: null,
        message: ''
      };
    },
    methods: {
      handleFile(e) {
        this.justificatif = e.target.files[0];
      },
      async submitForm() {
        if (this.motDePasse !== this.confirmation) {
          this.message = "Les mots de passe ne correspondent pas.";
          return;
        }
  
        const formData = new FormData();
        formData.append("nomEtablissement", this.nomEtablissement);
        formData.append("email", this.email);
        formData.append("telephone", this.telephone);
        formData.append("motDePasse", this.motDePasse);
        formData.append("adresse", this.adresse);
        formData.append("typeHebergement", this.typeHebergement);
        formData.append("justificatif", this.justificatif);
  
        try {
          const res = await fetch("http://localhost/soutenance_web/backend/hote_register.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();
          this.message = data.message;
        } catch (err) {
          this.message = "Erreur lors de l’envoi.";
        }
        if (data.success) {
          this.message = "Compte créé avec succès !";
          this.$router.push("/login");
        }
      }
    }
  };
  </script>
  