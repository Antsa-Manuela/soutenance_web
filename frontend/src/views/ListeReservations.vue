<!-- frontend/src/views/ListeReservations.vue -->
<template>
    <div class="liste-reservations">
      <h2>Réservations reçues</h2>
      <div v-if="reservations.length === 0">Aucune réservation pour le moment.</div>
      <table v-else>
        <thead>
          <tr>
            <th>Client</th>
            <th>Dates</th>
            <th>Personnes</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in reservations" :key="r.id_reservation">
            <td>{{ r.nomClient }}</td>
            <td>{{ r.dateDebut }} → {{ r.dateFin }}</td>
            <td>{{ r.nbAdultes }}A / {{ r.nbEnfants }}E</td>
            <td>{{ r.statut }}</td>
            <td>
              <button @click="confirmer(r.id_reservation)">✅</button>
              <button @click="annuler(r.id_reservation)">❌</button>
              <button @click="contacter(r.id_client)">💬</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        reservations: []
      };
    },
    mounted() {
      const idHote = localStorage.getItem("idHote");
      if (!idHote) return;
  
      fetch(`http://localhost/soutenance_web/backend/api/get_reservations.php?id_hote=${idHote}`)
        .then(res => res.json())
        .then(data => {
          this.reservations = data.reservations || [];
        });
    },
    methods: {
    async confirmer(id) {
      await this.updateStatut(id, "confirmé");
    },
    async annuler(id) {
      await this.updateStatut(id, "annulé");
    },
    async updateStatut(id, statut) {
      const formData = new FormData();
      formData.append("id", id);
      formData.append("statut", statut);

      try {
        const res = await fetch("http://localhost/soutenance_web/backend/api/update_reservation_statut.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        if (data.success) {
          this.reservations = this.reservations.map(r =>
            r.id_reservation === id ? { ...r, statut } : r
          );
        } else {
          alert(data.message || "Erreur lors de la mise à jour.");
        }
      } catch (err) {
        alert("Erreur serveur.");
      }
    },
    contacter(idClient) {
      alert("Contacter le client " + idClient);
    }
  }
  };
  </script>
  
  <style scoped>
  .liste-reservations {
    padding: 20px;
    color: black;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }
  th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
  }
  thead {
    background-color: #f0f0f0;
  }
  button {
    margin-right: 5px;
    padding: 5px 10px;
  }
  </style>
  