<!-- frontend/src/views/Login.vue -->
<template>
    <div class="login">
      <h2>Connexion Hôte</h2>
      <form @submit.prevent="submitLogin">
        <input v-model="email" type="email" placeholder="Email" required />
        <input v-model="motDePasse" type="password" placeholder="Mot de passe" required />
        <button type="submit">Se connecter</button>
      </form>
      <p v-if="message">{{ message }}</p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        email: '',
        motDePasse: '',
        message: ''
      };
    },
    methods: {
      async submitLogin() {
        const formData = new FormData();
        formData.append("email", this.email);
        formData.append("motDePasse", this.motDePasse);
  
        try {
          const res = await fetch("http://localhost/soutenance_web/backend/hote_login.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();
        if (data.success) {
          localStorage.setItem("nomEtablissement", data.nomEtablissement);
          localStorage.setItem("idHote", data.idHote); // ← cette ligne est essentielle
          this.$router.push("/dashboard");
        }
        else {
                    this.message = data.message;
                }
                } catch (err) {
                this.message = "Erreur de connexion.";
                }
      }
    }
  };
  </script>
  