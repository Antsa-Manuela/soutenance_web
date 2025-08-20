<!-- frontend/src/views/AdminDashboard.vue -->
<template>
  <div class="admin-dashboard">
    <header class="admin-header">
      <div class="header-left">
        <img src="/logo.svg" alt="Logo" class="logo" />
        <span class="app-name">Soutenance Web</span>
      </div>
      <div class="header-right">
        <div class="admin-info">
          <span class="welcome-text">Bienvenue, </span>
          <strong class="admin-name">{{ nomAdmin }}</strong>
        </div>
        <button class="logout-btn" @click="logout" title="Déconnexion">
          <svg xmlns="http://www.w3.org/2000/svg" class="power-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2v10" />
            <path d="M6.2 6.2a9 9 0 1 0 11.6 0" />
          </svg>
        </button>
      </div>
    </header>

    <div class="dashboard-body">
      <!-- Section Statistiques -->
      <div class="stats-overview">
        <div class="stat-card">
          <div class="stat-icon users">👥</div>
          <div class="stat-content">
            <span class="stat-value">{{ stats.totalUsers }}</span>
            <span class="stat-label">Utilisateurs total</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon events">📅</div>
          <div class="stat-content">
            <span class="stat-value">{{ stats.totalEvents }}</span>
            <span class="stat-label">Événements ce mois</span>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon revenue">💰</div>
          <div class="stat-content">
            <span class="stat-value">{{ stats.totalRevenue }}</span>
            <span class="stat-label">Revenue total</span>
          </div>
        </div>
      </div>

      <!-- Sections principales -->
      <div class="main-sections">
        <div class="chart-section" @click="$router.push('/gestion-utilisateurs')">
          <div class="section-header">
            <h3><i class="icon-users">👥</i> Utilisateurs par rôle</h3>
            <span class="view-all">Voir détails →</span>
          </div>
          <canvas id="userChart"></canvas>
          <div class="chart-legend">
            <span class="legend-item">
              <span class="legend-color admin"></span>
              Administrateurs
            </span>
            <span class="legend-item">
              <span class="legend-color hote"></span>
              Hôtes
            </span>
            <span class="legend-item">
              <span class="legend-color client"></span>
              Clients
            </span>
          </div>
        </div>

        <div class="event-section" @click="$router.push('/evenements')">
          <div class="section-header">
            <h3><i class="icon-calendar">📅</i> Gestion des événements</h3>
            <span class="view-all">Voir tout →</span>
          </div>
          <p class="section-description">Accédez à la liste complète des événements liés à l'hôtellerie.</p>
          
          <div class="quick-stats">
            <div class="stat-mini">
              <span class="mini-value">{{ stats.eventsToday }}</span>
              <span class="mini-label">Aujourd'hui</span>
            </div>
            <div class="stat-mini">
              <span class="mini-value">{{ stats.eventsWeek }}</span>
              <span class="mini-label">Cette semaine</span>
            </div>
          </div>
          
          <button class="primary action-button">
            <span>Voir les événements</span>
            <i class="icon-arrow">→</i>
          </button>
        </div>
      </div>

      <!-- Actions rapides -->
      <div class="quick-actions">
        <h3>Actions rapides</h3>
        <div class="actions-grid">
          <button class="action-btn" @click="$router.push('/ajouter-utilisateur')">
            <i class="action-icon">➕</i>
            <span>Ajouter utilisateur</span>
          </button>
          <button class="action-btn" @click="$router.push('/creer-evenement')">
            <i class="action-icon">🎉</i>
            <span>Créer événement</span>
          </button>
          <button class="action-btn" @click="$router.push('/rapports')">
            <i class="action-icon">📊</i>
            <span>Générer rapport</span>
          </button>
          <button class="action-btn" @click="$router.push('/parametres')">
            <i class="action-icon">⚙️</i>
            <span>Paramètres</span>
          </button>
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
      nomAdmin: localStorage.getItem("nomAdmin") || "Admin",
      stats: {
        totalUsers: 0,
        totalEvents: 0,
        totalRevenue: "42.5K",
        eventsToday: 0,
        eventsWeek: 0
      },
      evenements: []
    };
  },
  mounted() {
    fetch("http://localhost/soutenance_web/backend/api/get_dashboard_stats.php")
      .then(res => res.json())
      .then(data => {
        this.renderUserChart(data.roles);
        this.evenements = data.evenements || [];
        this.stats.totalUsers = data.totalUsers || 0;
        this.stats.totalEvents = data.totalEvents || 0;
        this.stats.totalRevenue = data.totalRevenue || "0 Ar";
        this.stats.eventsToday = data.eventsToday || 0;
        this.stats.eventsWeek = data.eventsWeek || 0;
      })
      .catch(err => {
        console.error("Erreur chargement dashboard :", err);
        this.evenements = [];
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
            backgroundColor: [
              "rgba(105, 20, 14, 0.9)",
              "rgba(213, 137, 54, 0.9)",
              "rgba(255, 249, 79, 0.9)"
            ],
            borderColor: [
              "rgba(105, 20, 14, 1)",
              "rgba(213, 137, 54, 1)",
              "rgba(255, 249, 79, 1)"
            ],
            borderWidth: 2,
            borderRadius: 6
          }]
        },
        options: {
          cutout: '65%',
          plugins: {
            legend: { display: false },
            tooltip: {
              bodyFont: {
                family: 'Montserrat',
                size: 14
              }
            }
          },
          animation: {
            animateScale: true,
            animateRotate: true
          }
        }
      });
    },
    logout() {
      localStorage.removeItem("nomAdmin");
      this.$router.push("/admin-login");
    }
  }
};
</script>

<style scoped>
.admin-dashboard {
  position: absolute;
  left: 0;
  right: 0;
  top: 5vw;
  background: linear-gradient(135deg, 
    rgba(60, 21, 24, 0.05) 0%, 
    rgba(245, 245, 245, 0.95) 50%,
    rgba(213, 137, 54, 0.05) 100%);
  font-family: var(--font-main);
}

/* 🔝 Header amélioré */
.admin-header {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, var(--color-bg-dark) 0%, var(--color-primary) 100%);
  color: var(--color-text-light);
  padding: 1rem 2rem;
  box-shadow: 0 4px 20px rgba(60, 21, 24, 0.2);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo {
  height: 40px;
  filter: drop-shadow(0 2px 4px rgba(255, 249, 79, 0.3));
}

.app-name {
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--color-highlight);
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.admin-info {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.welcome-text {
  font-size: 0.9rem;
  opacity: 0.9;
}

.admin-name {
  font-size: 1.1rem;
  color: var(--color-highlight);
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.15);
  color: var(--color-text-light);
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 0.5rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
}

.logout-btn:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-1px);
}

.power-icon {
  width: 18px;
  height: 18px;
}

.logout-text {
  font-size: 0.9rem;
  font-weight: 500;
}

/* 🧩 Corps du dashboard */
.dashboard-body {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

/* Vue d'ensemble des statistiques */
.stats-overview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform 0.3s ease;
}


.stat-icon {
  font-size: 2rem;
  padding: 1rem;
  border-radius: 12px;
}

.stat-icon.users {
  background: rgba(105, 20, 14, 0.1);
  color: var(--color-primary);
}

.stat-icon.events {
  background: rgba(213, 137, 54, 0.1);
  color: var(--color-accent);
}

.stat-icon.revenue {
  background: rgba(255, 249, 79, 0.1);
  color: var(--color-highlight);
}

.stat-content {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: var(--color-primary);
}

.stat-label {
  color: var(--color-text-dark);
  opacity: 0.8;
  font-size: 0.9rem;
}

/* Sections principales */
.main-sections {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.chart-section, .event-section {
  background: white;
  padding: 2rem;
  border-radius: 20px;
  box-shadow: 0 6px 18px rgba(60, 21, 24, 0.08);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  border: 1px solid rgba(60, 21, 24, 0.05);
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.section-header h3 {
  color: var(--color-primary);
  margin: 0;
  font-size: 1.3rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.view-all {
  color: var(--color-accent);
  font-size: 0.9rem;
  font-weight: 600;
  transition: color 0.2s;
}

.section-header:hover .view-all {
  color: var(--color-tertiary);
}

.section-description {
  color: var(--color-text-dark);
  opacity: 0.8;
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

/* Chart */
canvas {
  width: 100% !important;
  max-width: 280px;
  margin: 0 auto;
  height: auto !important;
  aspect-ratio: 1;
  display: block;
}

.chart-legend {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  margin-top: 1.5rem;
  flex-wrap: wrap;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  color: var(--color-text-dark);
}

.legend-color {
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.legend-color.admin {
  background: var(--color-primary);
}

.legend-color.hote {
  background: var(--color-accent);
}

.legend-color.client {
  background: var(--color-highlight);
}

/* Quick stats */
.quick-stats {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-mini {
  flex: 1;
  background: rgba(213, 137, 54, 0.1);
  padding: 1rem;
  border-radius: 12px;
  text-align: center;
}

.mini-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-accent);
  margin-bottom: 0.25rem;
}

.mini-label {
  font-size: 0.8rem;
  color: var(--color-text-dark);
  opacity: 0.8;
}

/* Boutons */
.action-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 1rem;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.action-button:hover {
  transform: translateX(5px);
}

/* Actions rapides */
.quick-actions {
  background: white;
  padding: 2rem;
  border-radius: 20px;
  box-shadow: 0 6px 18px rgba(60, 21, 24, 0.08);
}

.quick-actions h3 {
  color: var(--color-primary);
  margin-bottom: 1.5rem;
  font-size: 1.3rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.action-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  padding: 1.5rem 1rem;
  background: rgba(245, 245, 245, 0.5);
  border: 2px solid transparent;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
}

.action-btn:hover {
  background: white;
  border-color: var(--color-accent);
  transform: translateY(-3px);
  box-shadow: 0 8px 16px rgba(60, 21, 24, 0.1);
}

.action-icon {
  font-size: 2rem;
}

.action-btn span {
  font-weight: 600;
  color: var(--color-primary);
  font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
  .admin-header {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .stats-overview {
    grid-template-columns: 1fr;
  }
  
  .main-sections {
    grid-template-columns: 1fr;
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>