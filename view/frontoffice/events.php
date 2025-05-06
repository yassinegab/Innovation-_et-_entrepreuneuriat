<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements Entrepreneuriaux - EntrepreHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="events.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>EntrepreHub</h1>
                </div>
                <nav class="nav">
                    <button class="nav-toggle" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="nav-menu">
                        <li><a href="index.html">Accueil</a></li>
                        <li><a href="formulaire-evenement.php" class="active">Événements</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#" class="btn btn-primary">Commencer</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class="events-hero">
            <div class="container">
                <div class="events-hero-content">
                    <h1>Événements Entrepreneuriaux</h1>
                    <p>Découvrez des conférences, ateliers, et opportunités de réseautage pour développer vos compétences et faire grandir votre entreprise.</p>
                    <div class="events-search">
                        <div class="search-container">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" placeholder="Rechercher un événement..." class="search-input">
                        </div>
                        <button class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="events-filter">
            <div class="container">
                <div class="filter-container">
                    <div class="filter-group">
                        <label for="category">Catégorie</label>
                        <select id="category" class="filter-select">
                            <option value="all">Toutes les catégories</option>
                            <option value="workshop">Ateliers</option>
                            <option value="conference">Conférences</option>
                            <option value="networking">Réseautage</option>
                            <option value="hackathon">Hackathons</option>
                            <option value="pitch">Compétitions de pitch</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date">Date</label>
                        <select id="date" class="filter-select">
                            <option value="all">Toutes les dates</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="week">Cette semaine</option>
                            <option value="month">Ce mois</option>
                            <option value="quarter">Ce trimestre</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="format">Format</label>
                        <select id="format" class="filter-select">
                            <option value="all">Tous les formats</option>
                            <option value="in-person">En personne</option>
                            <option value="online">En ligne</option>
                            <option value="hybrid">Hybride</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="location">Lieu</label>
                        <select id="location" class="filter-select">
                            <option value="all">Tous les lieux</option>
                            <option value="paris">Paris</option>
                            <option value="lyon">Lyon</option>
                            <option value="marseille">Marseille</option>
                            <option value="bordeaux">Bordeaux</option>
                            <option value="online">En ligne</option>
                        </select>
                    </div>
                    <button class="btn btn-secondary filter-reset">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                        </svg>
                        Réinitialiser
                    </button>
                </div>
                <div class="view-toggle">
                    <button class="view-btn active" data-view="grid">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                        </svg>
                    </button>
                    <button class="view-btn" data-view="list">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                    </button>
                    <button class="view-btn" data-view="calendar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <section class="featured-events">
            <div class="container">
                <div class="section-header">
                    <h2>Événements à la une</h2>
                    <p>Ne manquez pas nos événements les plus populaires</p>
                </div>
                <div class="featured-events-slider">
                    <div class="featured-events-track">
                        <div class="featured-event-card">
                            <div class="event-image">
                                <img src="/placeholder.svg?height=250&width=500" alt="Sommet de l'Innovation Entrepreneuriale">
                                <div class="event-badge">À la une</div>
                            </div>
                            <div class="event-content">
                                <div class="event-date">
                                    <div class="date-box">
                                        <span class="date-day">15</span>
                                        <span class="date-month">Mai</span>
                                    </div>
                                </div>
                                <div class="event-details">
                                    <h3>Sommet de l'Innovation Entrepreneuriale</h3>
                                    <div class="event-meta">
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>09:00 - 18:00</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <span>Palais des Congrès, Paris</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <span>500+ participants</span>
                                        </div>
                                    </div>
                                    <p class="event-description">Rejoignez-nous pour une journée complète dédiée à l'innovation entrepreneuriale avec des conférenciers de renom, des ateliers pratiques et des opportunités de réseautage exceptionnelles.</p>
                                    <div class="event-tags">
                                        <span class="event-tag">Innovation</span>
                                        <span class="event-tag">Conférence</span>
                                        <span class="event-tag">Réseautage</span>
                                    </div>
                                    <div class="event-actions">
                                        <a href="#" class="btn btn-primary">S'inscrire</a>
                                        <a href="#" class="btn btn-secondary">Plus d'infos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="featured-event-card">
                            <div class="event-image">
                                <img src="/placeholder.svg?height=250&width=500" alt="Hackathon Startup Weekend">
                                <div class="event-badge">À la une</div>
                            </div>
                            <div class="event-content">
                                <div class="event-date">
                                    <div class="date-box">
                                        <span class="date-day">22</span>
                                        <span class="date-month">Mai</span>
                                    </div>
                                </div>
                                <div class="event-details">
                                    <h3>Hackathon Startup Weekend</h3>
                                    <div class="event-meta">
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            <span>Ven 18:00 - Dim 20:00</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <span>Station F, Paris</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <span>200+ participants</span>
                                        </div>
                                    </div>
                                    <p class="event-description">54 heures pour créer votre startup! Formez une équipe, validez votre idée, créez un prototype et présentez votre projet devant un jury d'experts et d'investisseurs.</p>
                                    <div class="event-tags">
                                        <span class="event-tag">Hackathon</span>
                                        <span class="event-tag">Startup</span>
                                        <span class="event-tag">Compétition</span>
                                    </div>
                                    <div class="event-actions">
                                        <a href="#" class="btn btn-primary">S'inscrire</a>
                                        <a href="#" class="btn btn-secondary">Plus d'infos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slider-nav">
                        <button class="nav-prev" aria-label="Previous event">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <div class="nav-dots">
                            <button class="nav-dot active" aria-label="Event 1"></button>
                            <button class="nav-dot" aria-label="Event 2"></button>
                        </div>
                        <button class="nav-next" aria-label="Next event">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="upcoming-events">
            <div class="container">
                <div class="section-header">
                    <h2>Événements à venir</h2>
                    <p>Planifiez votre agenda avec nos prochains événements</p>
                </div>
                <div class="events-grid">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Atelier Business Model Canvas">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">05</span>
                                    <span class="date-month">Juin</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Atelier</div>
                            <h3>Atelier Business Model Canvas</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>14:00 - 17:00</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Espace de Coworking, Lyon</span>
                                </div>
                            </div>
                            <p class="event-description">Apprenez à structurer votre modèle d'affaires avec l'outil Business Model Canvas lors de cet atelier pratique.</p>
                            <div class="event-footer">
                                <div class="event-price">30€</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Conférence Financement des Startups">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">12</span>
                                    <span class="date-month">Juin</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Conférence</div>
                            <h3>Financement des Startups</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>10:00 - 12:30</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>En ligne (Zoom)</span>
                                </div>
                            </div>
                            <p class="event-description">Découvrez les différentes options de financement disponibles pour les startups et comment préparer votre dossier.</p>
                            <div class="event-footer">
                                <div class="event-price">Gratuit</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Petit-déjeuner Networking">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">18</span>
                                    <span class="date-month">Juin</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Réseautage</div>
                            <h3>Petit-déjeuner Networking</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>08:00 - 10:00</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Café des Entrepreneurs, Marseille</span>
                                </div>
                            </div>
                            <p class="event-description">Commencez votre journée en rencontrant d'autres entrepreneurs autour d'un petit-déjeuner convivial.</p>
                            <div class="event-footer">
                                <div class="event-price">15€</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Masterclass Marketing Digital">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">25</span>
                                    <span class="date-month">Juin</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Formation</div>
                            <h3>Masterclass Marketing Digital</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>13:00 - 17:00</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Incubateur Bordeaux Technowest</span>
                                </div>
                            </div>
                            <p class="event-description">Maîtrisez les stratégies de marketing digital essentielles pour faire croître votre entreprise.</p>
                            <div class="event-footer">
                                <div class="event-price">50€</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Pitch Night">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">02</span>
                                    <span class="date-month">Juil</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Compétition</div>
                            <h3>Pitch Night</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>19:00 - 22:00</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Le Wagon, Paris</span>
                                </div>
                            </div>
                            <p class="event-description">Présentez votre startup en 3 minutes devant un panel d'investisseurs et recevez des feedbacks précieux.</p>
                            <div class="event-footer">
                                <div class="event-price">Gratuit</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="/placeholder.svg?height=200&width=400" alt="Table Ronde: Entrepreneuriat Féminin">
                            <div class="event-date">
                                <div class="date-box">
                                    <span class="date-day">10</span>
                                    <span class="date-month">Juil</span>
                                </div>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-category">Table Ronde</div>
                            <h3>Entrepreneuriat Féminin</h3>
                            <div class="event-meta">
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>18:30 - 20:30</span>
                                </div>
                                <div class="meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>Maison de l'Entrepreneuriat, Lyon</span>
                                </div>
                            </div>
                            <p class="event-description">Échangez avec des femmes entrepreneures inspirantes sur les défis et opportunités de l'entrepreneuriat féminin.</p>
                            <div class="event-footer">
                                <div class="event-price">Gratuit</div>
                                <a href="#" class="btn btn-primary btn-sm">S'inscrire</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="load-more-container">
                    <button class="btn btn-secondary load-more">
                        Voir plus d'événements
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <section class="events-calendar" id="calendar-view" style="display: none;">
            <div class="container">
                <div class="section-header">
                    <h2>Calendrier des événements</h2>
                    <p>Planifiez votre agenda avec notre calendrier interactif</p>
                </div>
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button class="calendar-nav prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <h3 class="calendar-title">Juin 2023</h3>
                        <button class="calendar-nav next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-weekdays">
                            <div>Lun</div>
                            <div>Mar</div>
                            <div>Mer</div>
                            <div>Jeu</div>
                            <div>Ven</div>
                            <div>Sam</div>
                            <div>Dim</div>
                        </div>
                        <div class="calendar-days">
                            <div class="calendar-day prev-month">29</div>
                            <div class="calendar-day prev-month">30</div>
                            <div class="calendar-day prev-month">31</div>
                            <div class="calendar-day">1</div>
                            <div class="calendar-day">2</div>
                            <div class="calendar-day">3</div>
                            <div class="calendar-day">4</div>
                            <div class="calendar-day">5
                                <div class="calendar-event workshop">Atelier BMC</div>
                            </div>
                            <div class="calendar-day">6</div>
                            <div class="calendar-day">7</div>
                            <div class="calendar-day">8</div>
                            <div class="calendar-day">9</div>
                            <div class="calendar-day">10</div>
                            <div class="calendar-day">11</div>
                            <div class="calendar-day">12
                                <div class="calendar-event conference">Financement</div>
                            </div>
                            <div class="calendar-day">13</div>
                            <div class="calendar-day">14</div>
                            <div class="calendar-day">15</div>
                            <div class="calendar-day">16</div>
                            <div class="calendar-day">17</div>
                            <div class="calendar-day">18
                                <div class="calendar-event networking">Networking</div>
                            </div>
                            <div class="calendar-day">19</div>
                            <div class="calendar-day">20</div>
                            <div class="calendar-day">21</div>
                            <div class="calendar-day">22</div>
                            <div class="calendar-day">23</div>
                            <div class="calendar-day">24</div>
                            <div class="calendar-day">25
                                <div class="calendar-event training">Marketing</div>
                            </div>
                            <div class="calendar-day">26</div>
                            <div class="calendar-day">27</div>
                            <div class="calendar-day">28</div>
                            <div class="calendar-day">29</div>
                            <div class="calendar-day">30</div>
                            <div class="calendar-day next-month">1</div>
                            <div class="calendar-day next-month">2
                                <div class="calendar-event competition">Pitch</div>
                            </div>
                        </div>
                    </div>
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <span class="legend-color workshop"></span>
                            <span>Ateliers</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color conference"></span>
                            <span>Conférences</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color networking"></span>
                            <span>Réseautage</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color training"></span>
                            <span>Formations</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color competition"></span>
                            <span>Compétitions</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="past-events">
            <div class="container">
                <div class="section-header">
                    <h2>Événements passés</h2>
                    <p>Revivez nos événements précédents et accédez aux ressources</p>
                </div>
                <div class="past-events-grid">
                    <div class="past-event-card">
                        <div class="past-event-image">
                            <img src="/placeholder.svg?height=180&width=320" alt="Conférence sur le Growth Hacking">
                            <div class="past-event-overlay">
                                <a href="#" class="btn btn-primary btn-sm">Voir l'enregistrement</a>
                            </div>
                        </div>
                        <div class="past-event-content">
                            <div class="past-event-date">15 Avril 2023</div>
                            <h3>Conférence sur le Growth Hacking</h3>
                            <p>Stratégies de croissance rapide pour les startups avec des experts du domaine.</p>
                            <div class="past-event-resources">
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Présentation
                                </a>
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Ressources
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="past-event-card">
                        <div class="past-event-image">
                            <img src="/placeholder.svg?height=180&width=320" alt="Workshop Design Thinking">
                            <div class="past-event-overlay">
                                <a href="#" class="btn btn-primary btn-sm">Voir l'enregistrement</a>
                            </div>
                        </div>
                        <div class="past-event-content">
                            <div class="past-event-date">22 Mars 2023</div>
                            <h3>Workshop Design Thinking</h3>
                            <p>Méthodologie d'innovation centrée sur l'humain pour résoudre des problèmes complexes.</p>
                            <div class="past-event-resources">
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Présentation
                                </a>
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Ressources
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="past-event-card">
                        <div class="past-event-image">
                            <img src="/placeholder.svg?height=180&width=320" alt="Conférence Fintech">
                            <div class="past-event-overlay">
                                <a href="#" class="btn btn-primary btn-sm">Voir l'enregistrement</a>
                            </div>
                        </div>
                        <div class="past-event-content">
                            <div class="past-event-date">10 Février 2023</div>
                            <h3>Conférence Fintech</h3>
                            <p>L'avenir des services financiers et les opportunités pour les entrepreneurs.</p>
                            <div class="past-event-resources">
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Présentation
                                </a>
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Ressources
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="past-event-card">
                        <div class="past-event-image">
                            <img src="/placeholder.svg?height=180&width=320" alt="Meetup Entrepreneurs Tech">
                            <div class="past-event-overlay">
                                <a href="#" class="btn btn-primary btn-sm">Voir les photos</a>
                            </div>
                        </div>
                        <div class="past-event-content">
                            <div class="past-event-date">25 Janvier 2023</div>
                            <h3>Meetup Entrepreneurs Tech</h3>
                            <p>Rencontre informelle entre entrepreneurs du secteur technologique.</p>
                            <div class="past-event-resources">
                                <a href="#" class="resource-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    Galerie photos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="propose-event">
            <div class="container">
                <div class="propose-event-content">
                    <div class="propose-event-text">
                        <h2>Vous avez une idée d'événement?</h2>
                        <p>Vous souhaitez organiser un événement pour la communauté entrepreneuriale ou devenir partenaire? Partagez votre idée avec nous et nous vous aiderons à la concrétiser.</p>
                        <a href="#" class="btn btn-primary">Proposer un événement</a>
                    </div>
                    <div class="propose-event-image">
                        <img src="/placeholder.svg?height=300&width=400" alt="Proposer un événement">
                    </div>
                </div>
            </div>
        </section>

        <section class="newsletter">
            <div class="container">
                <div class="newsletter-content">
                    <div class="newsletter-text">
                        <h2>Ne manquez aucun événement</h2>
                        <p>Inscrivez-vous à notre newsletter pour recevoir les dernières actualités et invitations à nos événements.</p>
                    </div>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Votre adresse email" required>
                        <button type="submit" class="btn btn-primary">S'abonner</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h2>EntrepreHub</h2>
                    <p>Votre partenaire dans le voyage entrepreneurial</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="footer-links">
                    <div class="footer-links-column">
                        <h3>Événements</h3>
                        <ul>
                            <li><a href="#">Événements à venir</a></li>
                            <li><a href="#">Événements passés</a></li>
                            <li><a href="#">Calendrier</a></li>
                            <li><a href="#">Proposer un événement</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Services</h3>
                        <ul>
                            <li><a href="#">Formation d'entreprise</a></li>
                            <li><a href="#">Mentorat</a></li>
                            <li><a href="#">Réseautage</a></li>
                            <li><a href="#">Financement</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Entreprise</h3>
                        <ul>
                            <li><a href="#">À propos</a></li>
                            <li><a href="#">Équipe</a></li>
                            <li><a href="#">Partenaires</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Légal</h3>
                        <ul>
                            <li><a href="#">Conditions d'utilisation</a></li>
                            <li><a href="#">Politique de confidentialité</a></li>
                            <li><a href="#">Cookies</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 EntrepreHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
    <script src="events.js"></script>
</body>
</html>
