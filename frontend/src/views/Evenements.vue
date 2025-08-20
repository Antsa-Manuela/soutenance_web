<!-- frontend/src/views/Evenements.vue -->
<template>
  <div class="evenements-container">
    <!-- Header cohérent -->
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

    <div class="evenements-content">
      <!-- En-tête de page -->
      <div class="page-header">
        <div class="title-section">
          <div class="icon-wrapper">
            <i class="icon-calendar">📅</i>
          </div>
          <h2>Gestion des Événements</h2>
        </div>
        <div class="header-stats">
        <button class="back-button" @click="$router.push('/admin-dashboard')">
          <i class="icon-home">🏠</i>
          Retour au dashboard
        </button>
          <div class="stat-badge">
            <span class="stat-count">{{ evenements.length }}</span>
            <span class="stat-label">événement(s)</span>
          </div>
        </div>
      </div>

      <!-- Formulaire d'ajout/modification -->
      <div class="form-section">
        <h3>{{ estEnModeEdition ? '✏️ Modifier l\'événement' : '➕ Ajouter un nouvel événement' }}</h3>
        <form @submit.prevent="estEnModeEdition ? modifierEvent() : ajouter()" class="event-form">
          <div class="form-grid">
            <div class="form-group">
              <label for="titre">Titre de l'événement *</label>
              <input 
                id="titre" 
                v-model="formData.titre" 
                placeholder="Ex: Salon de l'Hôtellerie 2024" 
                required 
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label for="dateDebut">Date de début *</label>
              <input 
                id="dateDebut" 
                v-model="formData.dateDebut" 
                type="date" 
                required 
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label for="dateFin">Date de fin *</label>
              <input 
                id="dateFin" 
                v-model="formData.dateFin" 
                type="date" 
                required 
                class="form-input"
              />
            </div>

            <div class="form-group full-width">
              <label for="description">Description courte</label>
              <textarea 
                id="description" 
                v-model="formData.description" 
                placeholder="Une brève description de l'événement..." 
                rows="3"
                class="form-textarea"
              ></textarea>
            </div>

            <div class="form-group full-width">
              <label for="resume">Fiche résumé (détails complets)</label>
              <textarea 
                id="resume" 
                v-model="formData.resume" 
                placeholder="Détails complets, programme, intervenants..." 
                rows="5"
                class="form-textarea"
              ></textarea>
            </div>

            <div class="form-group full-width">
              <label for="image" class="file-label">
                <i class="icon-upload">📸</i>
                {{ imageFile ? imageFile.name : (evenementEnCoursEdition?.image ? 'Image actuelle conservée' : 'Choisir une image') }}
              </label>
              <input 
                id="image" 
                type="file" 
                @change="handleImage" 
                accept="image/*" 
                class="file-input"
              />
              <small class="file-hint">Formats acceptés: JPG, PNG, WebP (Max 5MB)</small>
              
              <div v-if="evenementEnCoursEdition?.image && !imageFile" class="current-image-info">
                <small>Image actuelle: {{ evenementEnCoursEdition.image }}</small>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="submit-btn primary">
              <i class="action-icon">{{ estEnModeEdition ? '✏️' : '➕' }}</i>
              {{ estEnModeEdition ? 'Modifier' : 'Ajouter' }} l'événement
            </button>
            
            <button 
              v-if="estEnModeEdition" 
              type="button" 
              @click="annulerModification"
              class="cancel-btn"
            >
              <i class="action-icon">↩️</i>
              Annuler
            </button>
          </div>
        </form>
      </div>

      <!-- Séparateur -->
      <div class="section-divider">
        <span>Liste des événements</span>
      </div>

      <!-- Liste des événements -->
      <div v-if="evenements.length === 0" class="empty-state">
        <i class="empty-icon">📅</i>
        <h3>Aucun événement programmé</h3>
        <p>Commencez par ajouter votre premier événement</p>
      </div>

      <div v-else class="events-grid">
        <div v-for="e in evenements" :key="e.id_event" class="event-card">
          <div class="event-image" v-if="e.image && typeof e.image === 'string'">
            <img 
              :src="`http://localhost/soutenance_web/${e.image}`" 
              :alt="e.titre || 'Événement'"
              @error="handleImageError"
            />
            <div class="event-date-badge">
              {{ formatDate(e.date_debut) }}
            </div>
          </div>
          
          <div v-else class="event-image-placeholder">
            <i class="placeholder-icon">📅</i>
            <div class="event-date-badge">
              {{ formatDate(e.date_debut) }}
            </div>
          </div>
          
          <div class="event-content">
            <div class="event-header">
              <h3 class="event-title">{{ e.titre || 'Sans titre' }}</h3>
              <div class="event-dates">
                <span class="date-range">
                  📅 Du {{ formatDate(e.date_debut) }} au {{ formatDate(e.date_fin) }}
                </span>
              </div>
            </div>

            <div class="event-body">
              <p class="event-description" v-if="e.description">
                {{ e.description }}
              </p>
              <p class="event-description muted" v-else>
                Pas de description disponible
              </p>

              <div class="event-resume" v-if="e.resume">
                <h4>Détails :</h4>
                <p>{{ e.resume }}</p>
              </div>
            </div>

            <div class="event-actions">
              <button class="action-btn edit-btn" @click="modifier(e)" title="Modifier">
                <i class="action-icon">✏️</i>
                Modifier
              </button>
              <button class="action-btn delete-btn" @click="supprimer(e.id_event)" title="Supprimer">
                <i class="action-icon">🗑️</i>
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formData: {
        titre: '',
        dateDebut: '',
        dateFin: '',
        description: '',
        resume: ''
      },
      imageFile: null,
      evenements: [],
      evenementEnCoursEdition: null,
      estEnModeEdition: false,
      nomAdmin: localStorage.getItem("nomAdmin") || "Admin"
    };
  },
  mounted() {
    this.charger();
  },
  methods: {
    handleImage(e) {
      this.imageFile = e.target.files[0];
    },
    handleImageError(e) {
      e.target.style.display = "none";
      e.target.parentElement.classList.add('image-error');
    },
    formatDate(dateString) {
      if (!dateString) return '...';
      const date = new Date(dateString);
      return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    },
    async charger() {
      try {
        const res = await fetch("http://localhost/soutenance_web/backend/api/get_evenements.php");
        const data = await res.json();
        this.evenements = Array.isArray(data.evenements) ? data.evenements : [];
      } catch (err) {
        console.error("Erreur chargement événements :", err);
        this.evenements = [];
      }
    },
    
    // Pré-remplir le formulaire pour modification
    modifier(evenement) {
      this.evenementEnCoursEdition = evenement;
      this.estEnModeEdition = true;
      
      this.formData = {
        titre: evenement.titre || '',
        dateDebut: evenement.date_debut || '',
        dateFin: evenement.date_fin || '',
        description: evenement.description || '',
        resume: evenement.resume || ''
      };
      
      this.imageFile = null;
    },
    
    // Annuler la modification
    annulerModification() {
      this.estEnModeEdition = false;
      this.evenementEnCoursEdition = null;
      this.reinitialiserFormulaire();
    },
    
    // Réinitialiser le formulaire
    reinitialiserFormulaire() {
      this.formData = {
        titre: '',
        dateDebut: '',
        dateFin: '',
        description: '',
        resume: ''
      };
      this.imageFile = null;
    },
    
    async ajouter() {
      try {
        const formData = new FormData();
        formData.append("titre", this.formData.titre);
        formData.append("date_debut", this.formData.dateDebut);
        formData.append("date_fin", this.formData.dateFin);
        formData.append("description", this.formData.description);
        formData.append("resume", this.formData.resume);
        formData.append("id_admin", localStorage.getItem("idAdmin"));
        
        if (this.imageFile) {
          formData.append("image", this.imageFile);
        }

        const res = await fetch("http://localhost/soutenance_web/backend/api/ajouter_evenement.php", {
          method: "POST",
          body: formData
        });
        
        const data = await res.json();
        alert(data.message);
        
        this.reinitialiserFormulaire();
        this.charger();
        
      } catch (err) {
        console.error("Erreur ajout événement :", err);
        alert("Erreur lors de l'ajout.");
      }
    },
    
    async modifierEvent() {
      try {
        const formData = new FormData();
        formData.append("id_event", this.evenementEnCoursEdition.id_event);
        formData.append("titre", this.formData.titre);
        formData.append("date_debut", this.formData.dateDebut);
        formData.append("date_fin", this.formData.dateFin);
        formData.append("description", this.formData.description);
        formData.append("resume", this.formData.resume);
        
        if (this.imageFile) {
          formData.append("image", this.imageFile);
        }

        const res = await fetch("http://localhost/soutenance_web/backend/api/modifier_evenement.php", {
          method: "POST",
          body: formData
        });
        
        const data = await res.json();
        alert(data.message);
        
        this.estEnModeEdition = false;
        this.evenementEnCoursEdition = null;
        this.reinitialiserFormulaire();
        this.charger();
        
      } catch (err) {
        console.error("Erreur modification événement :", err);
        alert("Erreur lors de la modification.");
      }
    },
    
    async supprimer(id) {
      if (!confirm("Êtes-vous sûr de vouloir supprimer cet événement ?")) return;
      
      try {
        const formData = new FormData();
        formData.append("id_event", id);

        const res = await fetch("http://localhost/soutenance_web/backend/api/supprimer_evenement.php", {
          method: "POST",
          body: formData
        });
        
        const data = await res.json();
        alert(data.message);
        this.charger();
        
      } catch (err) {
        console.error("Erreur suppression événement :", err);
        alert("Erreur lors de la suppression.");
      }
    },
    
    logout() {
      localStorage.removeItem("nomAdmin");
      localStorage.removeItem("idAdmin");
      this.$router.push("/admin-login");
    }
  }
};
</script>

<style scoped>
.evenements-container {
  position: absolute;
  top: 5vw;
  right: 0;
  left: 0;
  background: linear-gradient(135deg, 
    rgba(60, 21, 24, 0.03) 0%, 
    rgba(245, 245, 245, 0.95) 50%,
    rgba(213, 137, 54, 0.03) 100%);
  font-family: var(--font-main);
}

/* Header cohérent */
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

.back-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: white;
  color: var(--color-bg-dark);
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.back-button:hover {
  background: var(--color-accent);
  transform: translateY(-1px);
}

/* Contenu principal */
.evenements-content {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

/* En-tête de page */
.page-header {
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

.icon-calendar {
  font-size: 1.5rem;
}

h2 {
  color: var(--color-primary);
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0;
}

.header-stats {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-badge {
  background: var(--color-highlight);
  color: var(--color-bg-dark);
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-weight: 700;
  text-align: center;
}

.stat-count {
  font-size: 1.5rem;
  display: block;
}

.stat-label {
  font-size: 0.9rem;
  display: block;
}

/* Formulaire */
.form-section {
  background: white;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
  margin-bottom: 2rem;
}

.form-section h3 {
  color: var(--color-primary);
  margin-bottom: 1.5rem;
  font-size: 1.3rem;
}

.event-form {
  width: 100%;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

label {
  font-weight: 600;
  color: var(--color-text-dark);
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.form-input, .form-textarea {
  padding: 0.75rem 1rem;
  border: 2px solid var(--color-accent);
  border-radius: 8px;
  font-family: var(--font-main);
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--color-primary);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

/* Upload de fichier */
.file-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem;
  border: 2px dashed var(--color-accent);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  justify-content: center;
}

.file-label:hover {
  border-color: var(--color-primary);
  background: rgba(213, 137, 54, 0.05);
}

.file-input {
  display: none;
}

.file-hint {
  color: var(--color-text-dark);
  opacity: 0.7;
  font-size: 0.8rem;
  margin-top: 0.5rem;
}

.current-image-info {
  margin-top: 0.5rem;
  padding: 0.5rem;
  background: rgba(213, 137, 54, 0.1);
  border-radius: 4px;
  font-size: 0.8rem;
}

/* Actions du formulaire */
.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  align-items: center;
}

.submit-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-btn.primary {
  background: var(--color-primary);
  color: white;
}

.submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(60, 21, 24, 0.2);
}

.cancel-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  border: 2px solid var(--color-accent);
  background: transparent;
  color: var(--color-accent);
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.cancel-btn:hover {
  background: var(--color-accent);
  color: white;
  transform: translateY(-2px);
}

/* Séparateur */
.section-divider {
  text-align: center;
  margin: 2rem 0;
  position: relative;
}

.section-divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: var(--color-accent);
  opacity: 0.3;
}

.section-divider span {
  background: white;
  padding: 0 1rem;
  color: var(--color-primary);
  font-weight: 600;
  position: relative;
  z-index: 1;
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

/* Grille d'événements */
.events-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}

.event-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(60, 21, 24, 0.08);
  transition: all 0.3s ease;
}

.event-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(60, 21, 24, 0.12);
}

.event-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.event-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.event-card:hover .event-image img {
  transform: scale(1.05);
}

.event-image-placeholder {
  position: relative;
  height: 200px;
  background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-highlight) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.placeholder-icon {
  font-size: 3rem;
}

.event-image.image-error {
  background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-highlight) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}

.event-image.image-error::before {
  content: '📅';
  font-size: 3rem;
}

.event-date-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: var(--color-primary);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.event-content {
  padding: 1.5rem;
}

.event-header {
  margin-bottom: 1rem;
}

.event-title {
  color: var(--color-primary);
  font-size: 1.2rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
}

.event-dates {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.date-range {
  color: var(--color-text-dark);
  opacity: 0.8;
  font-size: 0.9rem;
}

.event-body {
  margin-bottom: 1.5rem;
}

.event-description {
  color: var(--color-text-dark);
  line-height: 1.6;
  margin: 0 0 1rem 0;
}

.event-description.muted {
  opacity: 0.6;
  font-style: italic;
}

.event-resume {
  border-top: 1px solid rgba(60, 21, 24, 0.1);
  padding-top: 1rem;
}

.event-resume h4 {
  color: var(--color-primary);
  font-size: 1rem;
  margin: 0 0 0.5rem 0;
}

.event-resume p {
  color: var(--color-text-dark);
  line-height: 1.6;
  margin: 0;
  font-size: 0.9rem;
}

.event-actions {
  display: flex;
  gap: 0.75rem;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.edit-btn {
  background: var(--color-accent);
  color: var(--color-bg-dark);
}

.edit-btn:hover {
  background: var(--color-highlight);
  transform: translateY(-1px);
}

.delete-btn {
  background: #dc3545;
  color: white;
}

.delete-btn:hover {
  background: #c82333;
  transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
  .admin-header {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-right {
    flex-direction: column;
    gap: 1rem;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .events-grid {
    grid-template-columns: 1fr;
  }
  
  .event-actions {
    flex-direction: column;
  }
  
  .page-header {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
}
</style>