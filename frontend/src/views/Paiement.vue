<!-- frontend/src/views/Paiements.vue -->
<template>
    <div>
      <h2>Paiements reçus</h2>
      <table>
        <thead>
          <tr>
            <th>Montant</th>
            <th>Méthode</th>
            <th>Date</th>
            <th>Remboursé</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in paiements" :key="p.id_paiement">
            <td>{{ p.montant }} Ar</td>
            <td>{{ p.methode }}</td>
            <td>{{ p.date }}</td>
            <td>{{ p.rembourse ? "Oui" : "Non" }}</td>
            <td>
              <button v-if="!p.rembourse" @click="rembourser(p.id_paiement)">Rembourser</button>
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
        paiements: []
      };
    },
    mounted() {
      const idHote = localStorage.getItem("idHote");
      fetch(`http://localhost/soutenance_web/backend/api/get_paiements.php?id_hote=${idHote}`)
        .then(res => res.json())
        .then(data => {
          this.paiements = data.paiements || [];
        });
    },
    methods: {
      async rembourser(idPaiement) {
        const formData = new FormData();
        formData.append("id_paiement", idPaiement);
  
        const res = await fetch("http://localhost/soutenance_web/backend/api/rembourser_paiement.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        location.reload();
      }
    }
  };
  </script>
  