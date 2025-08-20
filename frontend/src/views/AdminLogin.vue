<!-- frontend/src/views/AdminLogin.vue -->
<template>
  <div class="login-wrapper">
    <!-- Partie gauche -->
    <div class="login-left">
      <img src="/logo.svg" alt="Logo" class="logo" />
      <h1 class="app-name">Soutenance Web</h1>
      <div class="login-box">
        <form @submit.prevent="login">
          <input v-model="email" type="email" placeholder="Email" required />
          <input v-model="motDePasse" type="password" placeholder="Mot de passe" required />
          <button type="submit">Se connecter</button>
        </form>
        <p v-if="message" class="error">{{ message }}</p>
      </div>
    </div>

    <!-- Partie droite -->
    <div class="login-right">
      <img src="/baobao.jpg" alt="Baobab" class="background-image" />
      <div class="welcome-message">
        <h2>🌿 Bon retour parmi nous !</h2>
        <p>Accédez à votre espace administrateur en toute sérénité.</p>
      </div>
    </div>
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
.login-wrapper {
  display: flex;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background-color: #3C1518;
  font-family: var(--font-main);
}

/* Partie gauche */
.login-left {
  flex: 1;
  background-color: white;
  padding: 4vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.logo {
  width: 80px;
  margin-bottom: 10px;
}

.app-name {
  font-size: 1.5rem;
  color: var(--color-primary);
  margin-bottom: 30px;
}

.login-box {
  width: 50%;
  max-width: 400px;
  align-self: center;
}

.login-box h2 {
  margin-bottom: 20px;
  color: var(--color-bg-dark);
  text-align: center;
}

input {
  display: block;
  width: 100%;
  margin-bottom: 15px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

button {
  background-color: var(--color-primary);
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
  width: 100%;
}

button:hover {
  background-color: var(--color-tertiary);
}

.error {
  color: red;
  margin-top: 10px;
}

/* Partie droite */
.login-right {
  flex: 1;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.background-image {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.3;
}

.welcome-message {
  position: relative;
  text-align: center;
  color: white;
  z-index: 1;
}

h2{
  color: #d58936;
  font-weight: bold;
}

.welcome-message h2 {
  font-size: 2rem;
  margin-bottom: 10px;
}

.welcome-message p {
  font-size: 1.1rem;
}
</style>
