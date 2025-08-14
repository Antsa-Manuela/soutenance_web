<!-- frontend/src/views/Messagerie.vue -->
<template>
  <div class="messagerie">
    <h2>Messagerie</h2>
    <div v-if="error" class="error">{{ error }}</div>

    <!-- Liste des clients si aucun client sélectionné -->
    <div v-if="!idClient">
      <p>Choisissez un client pour ouvrir une conversation :</p>
      <ul>
        <li v-for="client in clients" :key="client.id_client">
          <router-link :to="`/messagerie?idClient=${client.id_client}`">
            💬 {{ client.nom }}
          </router-link>
        </li>
      </ul>
    </div>

    <!-- Conversation si client sélectionné -->
    <div v-else>
      <div v-for="msg in messages" :key="msg.id_message" class="message">
        <strong>{{ msg.id_expediteur == idHote ? 'Moi' : 'Client' }}:</strong> {{ msg.contenu }}
      </div>

      <input v-model="nouveauMessage" placeholder="Votre message..." />
      <button @click="envoyerMessage">Envoyer</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      messages: [],
      nouveauMessage: '',
      error: '',
      idHote: localStorage.getItem("idHote"),
      idClient: this.$route.query.idClient || null,
      clients: []
    };
  },
  mounted() {
    if (!this.idHote) {
      this.error = "ID hôte manquant.";
      return;
    }

    if (!this.idClient) {
      this.chargerClients();
    } else {
      this.chargerMessages();
    }
  },
  methods: {
    chargerClients() {
      fetch(`http://localhost/soutenance_web/backend/api/get_clients_messagerie.php?idHote=${this.idHote}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) this.error = data.error;
          else this.clients = data;
        });
    },
    chargerMessages() {
      fetch(`http://localhost/soutenance_web/backend/api/get_messages.php?id1=${this.idHote}&id2=${this.idClient}`)
        .then(res => res.json())
        .then(data => {
          if (data.error) this.error = data.error;
          else this.messages = data;
        });
    },
    envoyerMessage() {
      if (!this.nouveauMessage.trim()) return;
      fetch("http://localhost/soutenance_web/backend/api/envoyer_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_expediteur: this.idHote,
          id_destinataire: this.idClient,
          contenu: this.nouveauMessage
        })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.nouveauMessage = '';
            this.chargerMessages();
          } else {
            this.error = data.error;
          }
        });
        this.$nextTick(() => {
          const container = this.$el.querySelector(".messagerie");
          if (container) {
            container.scrollTop = container.scrollHeight;
          }
        });
    }
  },
  watch: {
  '$route.query.idClient'(newId) {
    this.idClient = newId;
    this.chargerMessages();
  }
}

};
</script>

<style scoped>
.messagerie {
  padding: 20px;
  font-family: Arial, sans-serif;
  color: black;
}
.message {
  margin-bottom: 10px;
}
.error {
  color: red;
}
</style>
