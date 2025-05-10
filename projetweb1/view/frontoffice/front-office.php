<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hackathon Innovation Tech - Inscription</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="theme-custom.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <style>
    .event-header {
      position: relative;
      height: 400px;
      overflow: hidden;
      border-radius: var(--radius-lg);
      margin-bottom: 2rem;
    }
    
    .event-header-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.7);
    }
    
    .event-header-content {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 2rem;
      background: linear-gradient(to top, rgba(13, 17, 23, 0.9), rgba(13, 17, 23, 0));
      color: var(--color-text-primary);
    }
    
    .event-category {
      display: inline-block;
      padding: 0.5rem 1rem;
      background-color: var(--color-accent-primary);
      color: var(--color-bg-primary);
      font-weight: 600;
      border-radius: var(--radius-md);
      margin-bottom: 1rem;
    }
    
    .event-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-top: 1rem;
    }
    
    .event-meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--color-text-secondary);
    }
    
    .event-content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 2rem;
    }
    
    .event-description {
      background-color: var(--color-bg-secondary);
      border-radius: var(--radius-lg);
      padding: 2rem;
      border: 1px solid var(--color-border-primary);
    }
    
    .event-description h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--color-border-primary);
    }
    
    .event-description p {
      margin-bottom: 1.5rem;
      line-height: 1.8;
    }
    
    .event-sidebar {
      background-color: var(--color-bg-secondary);
      border-radius: var(--radius-lg);
      padding: 2rem;
      border: 1px solid var(--color-border-primary);
      position: sticky;
      top: 2rem;
    }
    
    .event-sidebar h3 {
      font-size: 1.25rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--color-border-primary);
    }
    
    .event-info-list {
      margin-bottom: 2rem;
    }
    
    .event-info-item {
      display: flex;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--color-border-primary);
    }
    
    .event-info-item:last-child {
      border-bottom: none;
    }
    
    .event-info-icon {
      flex: 0 0 40px;
      height: 40px;
      background-color: rgba(88, 166, 255, 0.1);
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--color-accent-primary);
      margin-right: 1rem;
    }
    
    .event-info-content {
      flex: 1;
    }
    
    .event-info-label {
      font-size: 0.875rem;
      color: var(--color-text-tertiary);
      margin-bottom: 0.25rem;
    }
    
    .event-info-value {
      font-weight: 600;
    }
    
    .event-price {
      font-size: 2rem;
      font-weight: 700;
      color: var(--color-accent-primary);
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .event-actions {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .event-actions .btn-primary {
      padding: 1rem;
      font-size: 1rem;
    }
    
    .event-actions .btn-secondary {
      padding: 1rem;
      font-size: 1rem;
    }
    
    .event-organizer {
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--color-border-primary);
    }
    
    .organizer-info {
      display: flex;
      align-items: center;
      margin-top: 1rem;
    }
    
    .organizer-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: var(--color-bg-tertiary);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      overflow: hidden;
    }
    
    .organizer-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .organizer-details h4 {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }
    
    .organizer-details p {
      font-size: 0.875rem;
      color: var(--color-text-tertiary);
    }
    
    .event-features {
      margin-top: 2rem;
    }
    
    .feature-list {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    
    .feature-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .feature-item i {
      color: var(--color-success);
    }
    
    .registration-form {
      margin-top: 2rem;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    .form-group.full-width {
      grid-column: span 2;
    }
    
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    
    .modal-content {
      background-color: var(--color-bg-secondary);
      border-radius: var(--radius-md);
      padding: 2rem;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
    }
    
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .modal-close {
      background: none;
      border: none;
      color: var(--color-text-secondary);
      font-size: 1.5rem;
      cursor: pointer;
    }
    
    .modal-close:hover {
      color: var(--color-text-primary);
    }
    
    .success-message {
      text-align: center;
      padding: 2rem;
    }
    
    .success-icon {
      font-size: 4rem;
      color: var(--color-success);
      margin-bottom: 1rem;
    }
    
    .related-events {
      margin-top: 4rem;
    }
    
    .related-events h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }
    
    .related-events-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }
    
    .related-event-card {
      background-color: var(--color-bg-secondary);
      border: 1px solid var(--color-border-primary);
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: transform var(--transition-normal), box-shadow var(--transition-normal);
    }
    
    .related-event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    .related-event-image {
      height: 160px;
      overflow: hidden;
    }
    
    .related-event-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .related-event-content {
      padding: 1.5rem;
    }
    
    .related-event-category {
      display: inline-block;
      padding: 0.25rem 0.5rem;
      background-color: rgba(88, 166, 255, 0.1);
      color: var(--color-accent-primary);
      font-size: 0.75rem;
      border-radius: var(--radius-sm);
      margin-bottom: 0.5rem;
    }
    
    .related-event-title {
      font-size: 1.125rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .related-event-meta {
      display: flex;
      justify-content: space-between;
      color: var(--color-text-tertiary);
      font-size: 0.875rem;
    }
    
    @media (max-width: 992px) {
      .event-content {
        grid-template-columns: 1fr;
      }
      
      .event-sidebar {
        position: static;
      }
      
      .related-events-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .form-group.full-width {
        grid-column: span 1;
      }
      
      .related-events-grid {
        grid-template-columns: 1fr;
      }
      
      .feature-list {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="py-4 flex justify-between items-center">
        <div class="flex items-center">
          <h1 class="text-xl font-bold">EventHub</h1>
        </div>
        <nav class="hidden md:flex">
          <ul class="flex gap-6">
            <li><a href="#" class="hover:text-accent-primary">Accueil</a></li>
            <li><a href="#" class="hover:text-accent-primary">Événements</a></li>
            <li><a href="#" class="hover:text-accent-primary">Catégories</a></li>
            <li><a href="#" class="hover:text-accent-primary">À propos</a></li>
            <li><a href="#" class="hover:text-accent-primary">Contact</a></li>
          </ul>
        </nav>
        <div class="flex items-center gap-4">
          <a href="#" class="btn-secondary">Se connecter</a>
          <a href="#" class="btn-primary">S'inscrire</a>
        </div>
      </div>
    </div>
  </header>

  <main class="container py-8">
    <div class="event-header">
      <img src="/placeholder.svg?height=400&width=1200" alt="Hackathon Innovation Tech" class="event-header-image">
      <div class="event-header-content">
        <span class="event-category">Compétition</span>
        <h1 class="text-4xl font-bold">Hackathon Innovation Tech</h1>
        <div class="event-meta">
          <div class="event-meta-item">
            <i class="fas fa-calendar"></i>
            <span>20-21 Mai 2025</span>
          </div>
          <div class="event-meta-item">
            <i class="fas fa-clock"></i>
            <span>48h non-stop</span>
          </div>
          <div class="event-meta-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Campus Numérique, Marseille</span>
          </div>
          <div class="event-meta-item">
            <i class="fas fa-users"></i>
            <span>32 équipes inscrites</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="event-content">
      <div class="event-description">
        <h2>À propos de l'événement</h2>
        <p>
          Rejoignez-nous pour le plus grand hackathon tech de l'année ! Le Hackathon Innovation Tech est un événement de 48 heures où des équipes de développeurs, designers et entrepreneurs collaborent pour créer des solutions innovantes aux défis technologiques d'aujourd'hui.
        </p>
        <p>
          Cette année, nous nous concentrons sur trois thèmes principaux : l'intelligence artificielle éthique, les solutions durables, et l'accessibilité numérique. Les équipes auront accès à des mentors experts, des ressources techniques, et un environnement stimulant pour transformer leurs idées en prototypes fonctionnels.
        </p>
        
        <h2 class="mt-6">Ce qui vous attend</h2>
        <div class="feature-list mt-4">
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>48h de codage intensif</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>Mentorat par des experts</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>Repas et boissons offerts</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>Networking avec l'industrie</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>Prix pour les meilleures équipes</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-check-circle"></i>
            <span>Opportunités de recrutement</span>
          </div>
        </div>
        
        <h2 class="mt-6">Programme</h2>
        <p>
          <strong>Jour 1 (20 Mai)</strong>
        </p>
        <ul class="list-disc pl-6 mb-4">
          <li>09:00 - 10:00 : Accueil et enregistrement</li>
          <li>10:00 - 11:00 : Cérémonie d'ouverture et présentation des défis</li>
          <li>11:00 - 12:00 : Formation des équipes</li>
          <li>12:00 - 13:00 : Déjeuner</li>
          <li>13:00 : Début officiel du hackathon</li>
          <li>19:00 - 20:00 : Dîner</li>
          <li>22:00 : Sessions de mentorat</li>
        </ul>
        
        <p>
          <strong>Jour 2 (21 Mai)</strong>
        </p>
        <ul class="list-disc pl-6 mb-4">
          <li>08:00 - 09:00 : Petit-déjeuner</li>
          <li>12:00 - 13:00 : Déjeuner</li>
          <li>13:00 : Dernier point d'étape</li>
          <li>16:00 : Fin du codage</li>
          <li>16:00 - 18:00 : Présentations des projets</li>
          <li>18:00 - 19:00 : Délibération du jury</li>
          <li>19:00 - 20:00 : Annonce des gagnants et cérémonie de clôture</li>
        </ul>
        
        <h2 class="mt-6">Prix et récompenses</h2>
        <p>
          Les équipes gagnantes se partageront une cagnotte totale de 15 000€, ainsi que des opportunités de mentorat, d'incubation et de financement pour développer leurs projets.
        </p>
        <ul class="list-disc pl-6 mb-4">
          <li>1er prix : 7 000€ + Programme d'incubation de 6 mois</li>
          <li>2ème prix : 5 000€ + Mentorat personnalisé</li>
          <li>3ème prix : 3 000€</li>
          <li>Prix spécial "Innovation" : Matériel tech d'une valeur de 2 000€</li>
        </ul>
      </div>
      
      <div class="event-sidebar">
        <div class="event-price">150€ <span class="text-sm text-color-text-tertiary">par équipe</span></div>
        
        <div class="event-actions">
          <button class="btn-primary" id="register-btn">S'inscrire maintenant</button>
          <button class="btn-secondary">Ajouter au calendrier</button>
        </div>
        
        <div class="event-info-list mt-6">
          <div class="event-info-item">
            <div class="event-info-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="event-info-content">
              <div class="event-info-label">Date</div>
              <div class="event-info-value">20-21 Mai 2025</div>
            </div>
          </div>
          
          <div class="event-info-item">
            <div class="event-info-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="event-info-content">
              <div class="event-info-label">Horaires</div>
              <div class="event-info-value">48h non-stop (9h-20h)</div>
            </div>
          </div>
          
          <div class="event-info-item">
            <div class="event-info-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="event-info-content">
              <div class="event-info-label">Lieu</div>
              <div class="event-info-value">Campus Numérique</div>
              <div class="text-sm text-color-text-tertiary">123 Avenue de la Tech, 13000 Marseille</div>
            </div>
          </div>
          
          <div class="event-info-item">
            <div class="event-info-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="event-info-content">
              <div class="event-info-label">Participants</div>
              <div class="event-info-value">32 équipes (max. 4 personnes/équipe)</div>
            </div>
          </div>
          
          <div class="event-info-item">
            <div class="event-info-icon">
              <i class="fas fa-tag"></i>
            </div>
            <div class="event-info-content">
              <div class="event-info-label">Catégorie</div>
              <div class="event-info-value">Compétition</div>
            </div>
          </div>
        </div>
        
        <div class="event-organizer">
          <h3 class="text-sm uppercase text-color-text-tertiary">Organisé par</h3>
          <div class="organizer-info">
            <div class="organizer-avatar">
              <img src="/placeholder.svg?height=50&width=50" alt="TechInnovate">
            </div>
            <div class="organizer-details">
              <h4>TechInnovate</h4>
              <p>Organisateur d'événements tech</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="related-events">
      <h2>Autres événements qui pourraient vous intéresser</h2>
      <div class="related-events-grid">
        <div class="related-event-card">
          <div class="related-event-image">
            <img src="/placeholder.svg?height=160&width=300" alt="Workshop IA">
          </div>
          <div class="related-event-content">
            <span class="related-event-category">Workshop</span>
            <h3 class="related-event-title">Workshop Intelligence Artificielle</h3>
            <div class="related-event-meta">
              <span>10 Mai 2025</span>
              <span>Paris</span>
            </div>
          </div>
        </div>
        
        <div class="related-event-card">
          <div class="related-event-image">
            <img src="/placeholder.svg?height=160&width=300" alt="Conférence Cybersécurité">
          </div>
          <div class="related-event-content">
            <span class="related-event-category">Conférence</span>
            <h3 class="related-event-title">Conférence Cybersécurité 2025</h3>
            <div class="related-event-meta">
              <span>15 Juin 2025</span>
              <span>Lyon</span>
            </div>
          </div>
        </div>
        
        <div class="related-event-card">
          <div class="related-event-image">
            <img src="/placeholder.svg?height=160&width=300" alt="Networking Tech">
          </div>
          <div class="related-event-content">
            <span class="related-event-category">Networking</span>
            <h3 class="related-event-title">Soirée Networking Tech</h3>
            <div class="related-event-meta">
              <span>5 Mai 2025</span>
              <span>Bordeaux</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  
  <!-- Modal d'inscription -->
  <div id="registration-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="text-xl font-bold">Inscription au Hackathon Innovation Tech</h2>
        <button class="modal-close">&times;</button>
      </div>
      
      <form id="registration-form" class="registration-form">
        <div class="form-row">
          <div class="form-group">
            <label for="team-name">Nom de l'équipe</label>
            <input type="text" id="team-name" name="team-name" required>
          </div>
          <div class="form-group">
            <label for="team-size">Nombre de participants</label>
            <select id="team-size" name="team-size" required>
              <option value="">Sélectionner</option>
              <option value="1">1 personne</option>
              <option value="2">2 personnes</option>
              <option value="3">3 personnes</option>
              <option value="4">4 personnes</option>
            </select>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="contact-name">Nom du contact principal</label>
            <input type="text" id="contact-name" name="contact-name" required>
          </div>
          <div class="form-group">
            <label for="contact-email">Email du contact</label>
            <input type="email" id="contact-email" name="contact-email" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="contact-phone">Téléphone du contact</label>
            <input type="tel" id="contact-phone" name="contact-phone" required>
          </div>
          <div class="form-group">
            <label for="project-theme">Thème du projet</label>
            <select id="project-theme" name="project-theme" required>
              <option value="">Sélectionner un thème</option>
              <option value="ai">Intelligence artificielle éthique</option>
              <option value="sustainability">Solutions durables</option>
              <option value="accessibility">Accessibilité numérique</option>
            </select>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group full-width">
            <label for="project-description">Description du projet (idée initiale)</label>
            <textarea id="project-description" name="project-description" rows="4" required></textarea>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group full-width">
            <label for="team-skills">Compétences de l'équipe</label>
            <textarea id="team-skills" name="team-skills" rows="3" required></textarea>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group full-width">
            <label class="flex items-center gap-2">
              <input type="checkbox" required>
              <span>J'accepte les <a href="#" class="text-accent-primary">conditions de participation</a> et la <a href="#" class="text-accent-primary">politique de confidentialité</a>.</span>
            </label>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group full-width">
            <button type="submit" class="btn-primary w-full">Confirmer l'inscription</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Modal de confirmation -->
  <div id="confirmation-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="text-xl font-bold">Inscription confirmée !</h2>
        <button class="modal-close">&times;</button>
      </div>
      
      <div class="success-message">
        <div class="success-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="text-xl font-bold mb-4">Merci pour votre inscription !</h3>
        <p class="mb-4">Votre équipe est maintenant inscrite au Hackathon Innovation Tech. Vous recevrez un email de confirmation avec tous les détails pratiques.</p>
        <p class="mb-6">Numéro de référence : <strong>HAC-2025-<span id="reference-number">1234</span></strong></p>
        <button class="btn-primary" id="close-confirmation">Fermer</button>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <h2 class="text-xl font-bold">EventHub</h2>
          <p>La plateforme de référence pour tous vos événements tech et innovation.</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <div class="footer-links">
          <div class="footer-links-column">
            <h3>Événements</h3>
            <ul>
              <li><a href="#">Tous les événements</a></li>
              <li><a href="#">Ateliers</a></li>
              <li><a href="#">Conférences</a></li>
              <li><a href="#">Hackathons</a></li>
              <li><a href="#">Formations</a></li>
            </ul>
          </div>
          <div class="footer-links-column">
            <h3>À propos</h3>
            <ul>
              <li><a href="#">Notre mission</a></li>
              <li><a href="#">L'équipe</a></li>
              <li><a href="#">Témoignages</a></li>
              <li><a href="#">Partenaires</a></li>
              <li><a href="#">Presse</a></li>
            </ul>
          </div>
          <div class="footer-links-column">
            <h3>Ressources</h3>
            <ul>
              <li><a href="#">Blog</a></li>
              <li><a href="#">Guides</a></li>
              <li><a href="#">FAQ</a></li>
              <li><a href="#">Support</a></li>
              <li><a href="#">Contactez-nous</a></li>
            </ul>
          </div>
          <div class="footer-links-column">
            <h3>Légal</h3>
            <ul>
              <li><a href="#">Conditions d'utilisation</a></li>
              <li><a href="#">Politique de confidentialité</a></li>
              <li><a href="#">Cookies</a></li>
              <li><a href="#">Mentions légales</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 EventHub. Tous droits réservés.</p>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Gestion du modal d'inscription
      const registerBtn = document.getElementById('register-btn');
      const registrationModal = document.getElementById('registration-modal');
      const confirmationModal = document.getElementById('confirmation-modal');
      const modalCloses = document.querySelectorAll('.modal-close');
      const registrationForm = document.getElementById('registration-form');
      const closeConfirmation = document.getElementById('close-confirmation');
      
      if (registerBtn) {
        registerBtn.addEventListener('click', function() {
          registrationModal.style.display = 'flex';
        });
      }
      
      modalCloses.forEach(close => {
        close.addEventListener('click', function() {
          registrationModal.style.display = 'none';
          confirmationModal.style.display = 'none';
        });
      });
      
      if (closeConfirmation) {
        closeConfirmation.addEventListener('click', function() {
          confirmationModal.style.display = 'none';
        });
      }
      
      if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Générer un numéro de référence aléatoire
          const referenceNumber = Math.floor(1000 + Math.random() * 9000);
          document.getElementById('reference-number').textContent = referenceNumber;
          
          // Fermer le modal d'inscription et afficher la confirmation
          registrationModal.style.display = 'none';
          confirmationModal.style.display = 'flex';
          
          // Réinitialiser le formulaire
          registrationForm.reset();
        });
      }
      
      // Animation au défilement
      const animateOnScroll = () => {
        const elements = document.querySelectorAll('.event-description, .event-sidebar, .related-event-card');
        
        elements.forEach(element => {
          const elementTop = element.getBoundingClientRect().top;
          const elementVisible = 150;
          
          if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('animate-in');
          }
        });
      };
      
      // Ajouter des classes pour l'animation
      const elementsToAnimate = document.querySelectorAll('.event-description, .event-sidebar, .related-event-card');
      elementsToAnimate.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      });
      
      // Fonction pour l'animation
      document.addEventListener('scroll', animateOnScroll);
      
      // Déclencher l'animation au chargement initial
      animateOnScroll();
      
      // Classe pour l'animation
      document.head.insertAdjacentHTML('beforeend', `
        <style>
          .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
          }
        </style>
      `);
    });
  </script>
</body>
</html>
