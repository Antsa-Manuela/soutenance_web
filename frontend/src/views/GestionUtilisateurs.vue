<template>
  <div class="gestion-utilisateurs">
    <!-- 🔝 En-tête réutilisé -->
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

    <!-- 🧩 Corps -->
    <div class="utilisateurs-body">
      <div class="header-actions">
        <div class="title-section">
          <div class="icon-wrapper">
            <i class="icon-users">👥</i>
          </div>
          <h2>Gestion des Utilisateurs</h2>
        </div>
        <button class="back-button" @click="$router.push('/admin-dashboard')">
          <i class="icon-home">🏠</i>
          Retour au dashboard
        </button>
      </div>

      <!-- Filtres et contrôles -->
      <div class="controls-section">
        <div class="filter-group">
          <label for="role-filter" class="filter-label">Filtrer par rôle :</label>
          <select id="role-filter" v-model="filtreRole" @change="charger" class="role-select">
            <option value="">Tous les rôles</option>
            <option value="admin">Administrateurs</option>
            <option value="hote">Hôtes</option>
            <option value="client">Clients</option>
          </select>
        </div>
        
        <div class="stats-badge">
          <span class="stats-count">{{ utilisateurs.length }}</span>
          <span class="stats-label">utilisateur(s)</span>
        </div>
      </div>

      <!-- Liste des utilisateurs -->
      <div v-if="utilisateurs.length === 0" class="empty-state">
        <i class="empty-icon">👤</i>
        <h3>Aucun utilisateur trouvé</h3>
        <p>Essayez de modifier vos filtres de recherche</p>
      </div>

      <ul class="user-list" v-else>
        <li v-for="u in utilisateurs" :key="u.id" class="user-card" :class="`role-${u.role}`">
          <div class="user-avatar">
            <img v-if="u.photoProfil" :src="`http://localhost/soutenance_web/${u.photoProfil}`" alt="Profil" class="profil" />
            <div v-else class="avatar-placeholder">
              {{ u.nomComplet.charAt(0).toUpperCase() }}
            </div>
            <div class="role-badge" :class="u.role">
              {{ u.role }}
            </div>
          </div>
          
          <div class="user-info">
            <h3 class="user-name">{{ u.nomComplet }}</h3>
            <p class="user-email">{{ u.email }}</p>
            
            <div class="user-meta">
              <span class="meta-item">
                <i class="meta-icon">📧</i>
                {{ u.email }}
              </span>
            </div>
          </div>

          <div class="actions">
            <button class="action-btn delete-btn" @click="supprimer(u.id)" title="Supprimer">
              <i class="action-icon">🗑️</i>
              <span class="action-text">Supprimer</span>
            </button>
            
            <button v-if="u.role === 'hote'" class="action-btn promote-btn" @click="promouvoir(u.id)" title="Promouvoir admin">
              <i class="action-icon">⚡</i>
              <span class="action-text">Promouvoir</span>
            </button>
            
            <button v-if="u.role === 'admin'" class="action-btn demote-btn" @click="retrograder(u.id)" title="Rétrograder">
              <i class="action-icon">↩️</i>
              <span class="action-text">Rétrograder</span>
            </button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      utilisateurs: [],
      filtreRole: '',
      nomAdmin: localStorage.getItem("nomAdmin") || "Admin"
    };
  },
  mounted() {
    this.charger();
  },
  methods: {
    logout() {
      localStorage.removeItem("nomAdmin");
      this.$router.push("/admin-login");
    },
    async charger() {
      try {
        const url = this.filtreRole
          ? `http://localhost/soutenance_web/backend/api/get_utilisateurs.php?role=${this.filtreRole}`
          : `http://localhost/soutenance_web/backend/api/get_utilisateurs.php`;

        const res = await fetch(url);
        const data = await res.json();
        this.utilisateurs = data.utilisateurs || [];
      } catch (err) {
        console.error("Erreur chargement utilisateurs :", err);
      }
    },
    async supprimer(id) {
      if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.")) return;
      
      try {
        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/supprimer_utilisateur.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      } catch (error) {
        alert("Erreur lors de la suppression");
      }
    },
    async promouvoir(id) {
      if (!confirm("Promouvoir cet hôte en administrateur ?")) return;
      
      try {
        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/promouvoir_admin.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      } catch (error) {
        alert("Erreur lors de la promotion");
      }
    },
    async retrograder(id) {
      if (!confirm("Rétrograder cet administrateur en hôte ?")) return;
      
      try {
        const formData = new FormData();
        formData.append("id", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/retrograder_admin.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();
        alert(data.message);
        this.charger();
      } catch (error) {
        alert("Erreur lors de la rétrogradation");
      }
    }
  }
};
</script>

<style scoped>
.gestion-utilisateurs {
  position: absolute;
  top: 5vw;
  bottom: 0;
  right: 0;
  left: 0;
  background: linear-gradient(135deg, rgba(60, 21, 24, 0.05) 0%, rgba(245, 245, 245, 0.8) 100%);
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

/* 🧩 Corps principal */
.utilisateurs-body {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

/* En-tête avec actions */
.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
}

.title-section {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.icon-wrapper {
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-tertiary) 100%);
  padding: 0.75rem;
  border-radius: 12px;
}

.icon-users {
  font-size: 1.5rem;
}

h2 {
  color: var(--color-primary);
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0;
}

.back-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--color-accent);
  color: var(--color-bg-dark);
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.back-button:hover {
  background: var(--color-highlight);
  transform: translateY(-2px);
}

/* Contrôles et filtres */
.controls-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.filter-label {
  font-weight: 600;
  color: var(--color-text-dark);
}

.role-select {
  padding: 0.75rem 1rem;
  border: 2px solid var(--color-accent);
  border-radius: 8px;
  background: white;
  color: var(--color-text-dark);
  font-family: var(--font-main);
  cursor: pointer;
}

.stats-badge {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: var(--color-highlight);
  color: var(--color-bg-dark);
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-weight: 700;
}

.stats-count {
  font-size: 1.5rem;
}

.stats-label {
  font-size: 0.9rem;
}

/* État vide */
.empty-state {
  text-align: center;
  padding: 3rem;
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  color: var(--color-primary);
  margin-bottom: 0.5rem;
}

.empty-state p {
  color: var(--color-text-dark);
  opacity: 0.8;
}

/* Liste des utilisateurs */
.user-list {
  list-style: none;
  padding: 0;
  display: grid;
  gap: 1rem;
}

.user-card {
  display: flex;
  align-items: center;
  background: white;
  padding: 1.5rem;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
  transition: all 0.3s ease;
  gap: 1.5rem;
}

.user-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(60, 21, 24, 0.12);
}

/* Avatar */
.user-avatar {
  position: relative;
  flex-shrink: 0;
}

.profil {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--color-accent);
}

.avatar-placeholder {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-highlight) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 700;
  color: var(--color-bg-dark);
  border: 3px solid var(--color-accent);
}

.role-badge {
  position: absolute;
  bottom: -5px;
  right: -5px;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
}

.role-badge.admin {
  background: var(--color-primary);
  color: white;
}

.role-badge.hote {
  background: var(--color-accent);
  color: var(--color-bg-dark);
}

.role-badge.client {
  background: var(--color-highlight);
  color: var(--color-bg-dark);
}

/* Informations utilisateur */
.user-info {
  flex: 1;
}

.user-name {
  color: var(--color-primary);
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
}

.user-email {
  color: var(--color-text-dark);
  margin: 0 0 0.75rem 0;
  opacity: 0.8;
}

.user-meta {
  display: flex;
  gap: 1rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: var(--color-text-dark);
  opacity: 0.7;
}

/* Actions */
.actions {
  display: flex;
  gap: 0.75rem;
  flex-shrink: 0;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.delete-btn {
  background: #dc3545;
  color: white;
}

.delete-btn:hover {
  background: #c82333;
  transform: translateY(-1px);
}

.promote-btn {
  background: var(--color-accent);
  color: var(--color-bg-dark);
}

.promote-btn:hover {
  background: var(--color-highlight);
  transform: translateY(-1px);
}

.demote-btn {
  background: #6c757d;
  color: white;
}

.demote-btn:hover {
  background: #5a6268;
  transform: translateY(-1px);
}

.action-text {
  font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 768px) {
  .admin-header {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-actions {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
  
  .controls-section {
    flex-direction: column;
    gap: 1rem;
  }
  
  .user-card {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
  
  .actions {
    justify-content: center;
  }
}
</style>