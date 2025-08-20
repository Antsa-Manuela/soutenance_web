<!-- frontend/src/views/AdminDashboard.vue -->
<template>
  <div class="admin-dashboard">
    <header class="admin-header">
      <div class="header-left">
        <img src="/logo.svg" alt="Logo" class="logo" />
        <span class="app-name">Soutenance Web</span>
      </div>
      <div class="header-right">
        <strong>{{ nomAdmin }}</strong>
        <button class="logout-btn" @click="logout" title="Déconnexion">
          <svg xmlns="http://www.w3.org/2000/svg" class="power-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2v10" />
            <path d="M6.2 6.2a9 9 0 1 0 11.6 0" />
          </svg>
        </button>
      </div>
    </header>

    <div class="dashboard-body">
      <div class="chart-section" @click="$router.push('/gestion-utilisateurs')">
        <h3>Utilisateurs par rôle</h3>
        <canvas id="userChart"></canvas>
      </div>

      <div class="event-section" @click="$router.push('/evenements')">
        <div class="center"> 
          <!-- Comment aligner ceci centré horizontalement -->
          <h3>📅 Gérer les événements</h3>
          <p>Accédez à la liste complète des événements liés à l’hôtellerie.</p>
          <button class="primary">Voir les événements</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  Chart,
  DoughnutController,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js';

Chart.register(DoughnutController, ArcElement, Tooltip, Legend);

export default {
  data() {
    return {
      nomAdmin: localStorage.getItem("nomAdmin") || "Admin"
    };
  },
  mounted() {
    fetch("http://localhost/soutenance_web/backend/api/get_dashboard_stats.php")
      .then(res => res.json())
      .then(data => {
        this.renderUserChart(data.roles);
      });

  },
  methods: {
    renderUserChart(roles) {
      const ctx = document.getElementById("userChart");
      new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: roles.map(r => r.role),
          datasets: [{
            data: roles.map(r => r.total),
            backgroundColor: ["#69140E", "#D58936", "#FFF94F"]
          }]
        },
        options: {
          plugins: {
            legend: { position: "bottom" }
          }
        }
      });
    },
    logout() {
    localStorage.removeItem("nomAdmin");
    this.$router.push("/admin-login"); // ou la route de ton écran de connexion
  }
  }
};
</script>

<style scoped>
.admin-dashboard {
  background-color: #d58936;  
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  font-family: var(--font-main);
}

/* 🔝 Header */
.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #fff;
  color: var(--color-bg-dark);
  padding: 1vw 8vw;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  height: 10vw;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.logo {
  height: 40px;
}

.app-name {
  font-size: 1.2rem;
  font-weight: bold;
}

.header-right {
  font-size: 1rem;
}

/* 🧩 Corps du dashboard */
.dashboard-body {
  display: flex;
  gap: 40px;
  padding: 5vw;
  justify-content: space-between;
  flex-wrap: wrap;
}

/* 📊 Bloc donut chart */
.chart-section {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(60, 21, 24, 0.1);
  text-align: center;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  flex: 1;
  min-width: 300px;
  max-width: none;
}

.chart-section:hover {
  transform: scale(1.03); /* ← zoom léger */
  box-shadow: 0 6px 12px rgba(60, 21, 24, 0.2);
}

.chart-section h3 {
  color: var(--color-primary);
  margin-bottom: 10px;
}

/* 📅 Bloc événements */
.event-section {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(60, 21, 24, 0.1);
  text-align: center;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  flex: 1;
  min-width: 300px;
  max-width: none;
}

.event-section:hover {
  transform: scale(1.03); /* ← zoom léger */
  box-shadow: 0 6px 12px rgba(60, 21, 24, 0.2);
}

.event-section h3 {
  color: var(--color-primary);
  margin-bottom: 10px;
}

.event-section p {
  margin-bottom: 15px;
  color: var(--color-text-dark);
}

/* 📈 Canvas */
canvas {
  max-width: 300px;
  margin: auto;
}

.logout-btn {
  border: 1px solid var(--color-bg-dark);
  background-color: white;
  margin-left: 15px;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  transition: background-color 0.2s ease;
}
.logout-btn:hover {
  background-color: var(--color-bg-dark);
}

.power-icon {
  width: 2vw;
  height: 1.8vw;
}

.power-icon:hover{
  stroke: var(--color-light);
}

.center {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 9vw;
  justify-content: center;
}
</style>