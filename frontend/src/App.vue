<!-- frontend/src/App.vue -->
<template>
  <header>
    <img alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />
    <h1>Plateforme Hôte</h1>
  </header>
<!-- 
  <nav>
    <router-link to="/login">Connexion</router-link> |
    <router-link to="/register">Inscription</router-link> |
    <router-link to="/dashboard">Accueil</router-link>
  </nav>
 -->
  <main>
    <router-view />
  </main>
</template>

<script setup>
</script>

<style scoped>
header {
  line-height: 1.5;
  text-align: center;
  margin-bottom: 2rem;
}

.logo {
  display: block;
  margin: 0 auto 1rem;
}

main {
  max-width: 600px;
  margin: 0 auto;
  padding: 1rem;
  background: #f9f9f9;
  border-radius: 8px;
}
</style>
