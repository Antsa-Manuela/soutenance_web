<!-- frontend/src/views/Profil.vue -->
<template>
    <div class="profil">
      <h2>Profil établissement</h2>
      <form @submit.prevent="modifierProfil">
        <input v-model="profil.nomEtablissement" placeholder="Nom établissement" />
        <input v-model="profil.adresse" placeholder="Adresse" />
        <input v-model="profil.typeHebergement" placeholder="Type hébergement" />
        <input v-model="profil.email" placeholder="Email" />
        <input v-model="profil.telephone" placeholder="Téléphone" />
        <select v-model="profil.langue">
          <option value="FR">Français</option>
          <option value="MG">Malagasy</option>
        </select>
        <input type="file" @change="handlePhotoUpload" />
        <img v-if="previewPhoto" :src="previewPhoto" alt="Photo profil" style="max-width: 150px; margin-bottom: 10px;" />
        <button type="submit">Enregistrer</button>
        <p v-if="message">{{ message }}</p>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        profil: {},
        message: '',
        photoFile: null,
        previewPhoto: null
      };
    },
    mounted() {
      const idHote = localStorage.getItem("idHote");
      fetch(`http://localhost/soutenance_web/backend/api/get_profil_hote.php?id_hote=${idHote}`)
        .then(res => res.json())
        .then(data => {
          this.profil = data;
          this.profil.id_hote = idHote;
        });
    },
    methods: {
      handlePhotoUpload(e) {
        const file = e.target.files[0];
        this.photoFile = file;
        this.previewPhoto = URL.createObjectURL(file);
      },
      async modifierProfil() {
        const formData = new FormData();
        for (const key in this.profil) {
          formData.append(key, this.profil[key]);
        }
        if (this.photoFile) {
          formData.append('photoProfil', this.photoFile);
        }

        const res = await fetch("http://localhost/soutenance_web/backend/api/modifier_profil_hote.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        this.message = data.success ? "Profil mis à jour !" : data.message;
      }
    }
  };
  </script>
  
  <style scoped>
  .profil {
    padding: 20px;
    max-width: 600px;
    margin: auto;
    color: black;
  }
  input, select {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
  }
  button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
  }
  button:hover {
    background-color: #0056b3;
  }
  </style>
  