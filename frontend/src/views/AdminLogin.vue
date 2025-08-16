<!-- frontend/src/views/AdminLogin.vue -->
<template>
    <div class="admin-login">
      <h2>Connexion administrateur</h2>
      <form @submit.prevent="login">
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
      async login() {
        const formData = new FormData();
        formData.append("email", this.email);
        formData.append("mot_de_passe", this.motDePasse);
  
        const res = await fetch("http://localhost/soutenance_web/backend/api/admin_login.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        if (data.success) {
          localStorage.setItem("idAdmin", data.id_admin);
          localStorage.setItem("nomAdmin", data.nom);
          this.$router.push("/admin-dashboard");
        } else {
          this.message = data.message;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .admin-login {
    max-width: 400px;
    margin: auto;
    padding: 20px;
  }
  input {
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
  </style>
  