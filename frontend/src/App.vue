<!-- frontend/src/App.vue -->
<template>
  <Layout>
    <router-view />
  </Layout>
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
  max-width: fit-content;
  margin: 0 auto;
  padding: 1rem;
  background: #f9f9f9;
  border-radius: 8px;
}
</style>
