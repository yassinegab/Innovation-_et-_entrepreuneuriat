<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Événements</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>EventHub<span class="text-accent">Admin</span></h1>
                </div>
                <nav class="nav-menu">
                    <ul>
                        <li><a href="#" class="active">Événements</a></li>
                        <li><a href="#">Utilisateurs</a></li>
                        <li><a href="#">Statistiques</a></li>
                        <li><a href="#">Paramètres</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <div class="user-profile">
                        <img src="/placeholder.svg?height=40&width=40" alt="Avatar de l'administrateur">
                        <span>Admin</span>
                    </div>
                    <button class="btn-icon" id="theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>
                    <button class="btn-icon" id="notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <span class="badge">3</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section class="admin-dashboard">
            <div class="container">
                <div class="dashboard-header">
                    <h2>Gestion des Événements</h2>
                    <button class="btn btn-primary" id="add-event-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Ajouter un événement
                    </button>
                </div>

                <div class="dashboard-filters">
                    <div class="filter-group">
                        <label for="filter-category">Catégorie</label>
                        <select id="filter-category" class="filter-select">
                            <option value="">Toutes les catégories</option>
                            <option value="workshop">Atelier</option>
                            <option value="conference">Conférence</option>
                            <option value="networking">Networking</option>
                            <option value="training">Formation</option>
                            <option value="competition">Compétition</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-status">Statut</label>
                        <select id="filter-status" class="filter-select">
                            <option value="">Tous les statuts</option>
                            <option value="upcoming">À venir</option>
                            <option value="ongoing">En cours</option>
                            <option value="past">Passé</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-date">Date</label>
                        <input type="date" id="filter-date" class="filter-input">
                    </div>
                    <div class="filter-group search-group">
                        <label for="search-events">Rechercher</label>
                        <div class="search-input-wrapper">
                            <input type="text" id="search-events" placeholder="Rechercher un événement..." class="search-input">
                            <button class="search-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="events-table-container">
                    <table class="events-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                <th>Places</th>
                                <th>Inscrits</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="events-table-body">
                            <tr>
                                <td>EV-001</td>
                                <td>Conférence sur l'IA et le Machine Learning</td>
                                <td><span class="event-category conference">Conférence</span></td>
                                <td>15/04/2025</td>
                                <td>Paris</td>
                                <td>200</td>
                                <td>145</td>
                                <td><span class="event-status upcoming">À venir</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-001">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-001">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-001">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>EV-002</td>
                                <td>Atelier de développement web avec React</td>
                                <td><span class="event-category workshop">Atelier</span></td>
                                <td>22/04/2025</td>
                                <td>Lyon</td>
                                <td>30</td>
                                <td>28</td>
                                <td><span class="event-status upcoming">À venir</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-002">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-002">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-002">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>EV-003</td>
                                <td>Soirée Networking Tech Entrepreneurs</td>
                                <td><span class="event-category networking">Networking</span></td>
                                <td>05/05/2025</td>
                                <td>Bordeaux</td>
                                <td>100</td>
                                <td>67</td>
                                <td><span class="event-status upcoming">À venir</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-003">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-003">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-003">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>EV-004</td>
                                <td>Formation Cybersécurité Avancée</td>
                                <td><span class="event-category training">Formation</span></td>
                                <td>12/05/2025</td>
                                <td>Toulouse</td>
                                <td>50</td>
                                <td>32</td>
                                <td><span class="event-status upcoming">À venir</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-004">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-004">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-004">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>EV-005</td>
                                <td>Hackathon Développement Durable</td>
                                <td><span class="event-category competition">Compétition</span></td>
                                <td>20/05/2025</td>
                                <td>Lille</td>
                                <td>150</td>
                                <td>98</td>
                                <td><span class="event-status upcoming">À venir</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-005">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-005">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-005">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>EV-006</td>
                                <td>Conférence sur le Cloud Computing</td>
                                <td><span class="event-category conference">Conférence</span></td>
                                <td>01/03/2025</td>
                                <td>Marseille</td>
                                <td>120</td>
                                <td>120</td>
                                <td><span class="event-status past">Passé</span></td>
                                <td class="actions-cell">
                                    <button class="btn-icon edit-event" data-id="EV-006">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="btn-icon delete-event" data-id="EV-006">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="btn-icon view-registrations" data-id="EV-006">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <button class="pagination-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <span class="pagination-ellipsis">...</span>
                    <button class="pagination-btn">10</button>
                    <button class="pagination-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Modal pour ajouter/modifier un événement -->
        <div class="modal" id="event-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modal-title">Ajouter un événement</h3>
                    <button class="close-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="event-form">
                        <input type="hidden" id="event-id">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event-title">Titre de l'événement *</label>
                                <input type="text" id="event-title" required>
                            </div>
                            <div class="form-group">
                                <label for="event-category">Catégorie *</label>
                                <select id="event-category" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="workshop">Atelier</option>
                                    <option value="conference">Conférence</option>
                                    <option value="networking">Networking</option>
                                    <option value="training">Formation</option>
                                    <option value="competition">Compétition</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event-date">Date *</label>
                                <input type="date" id="event-date" required>
                            </div>
                            <div class="form-group">
                                <label for="event-time">Heure *</label>
                                <input type="time" id="event-time" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event-location">Lieu *</label>
                                <input type="text" id="event-location" required>
                            </div>
                            <div class="form-group">
                                <label for="event-capacity">Capacité *</label>
                                <input type="number" id="event-capacity" min="1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="event-description">Description *</label>
                            <textarea id="event-description" rows="5" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="event-price">Prix (€)</label>
                                <input type="number" id="event-price" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="event-image">Image</label>
                                <input type="file" id="event-image" accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="event-speakers">Intervenants</label>
                            <input type="text" id="event-speakers" placeholder="Noms séparés par des virgules">
                        </div>
                        <div class="form-group">
                            <label for="event-tags">Tags</label>
                            <input type="text" id="event-tags" placeholder="Tags séparés par des virgules">
                        </div>
                        <div class="form-group checkbox-group">
                            <label class="checkbox-container">
                                <input type="checkbox" id="event-featured">
                                <span class="checkmark"></span>
                                Événement à la une
                            </label>
                        </div>
                        <div class="form-group checkbox-group">
                            <label class="checkbox-container">
                                <input type="checkbox" id="event-registration-required">
                                <span class="checkmark"></span>
                                Inscription requise
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="cancel-event">Annuler</button>
                    <button class="btn btn-primary" id="save-event">Enregistrer</button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation de suppression -->
        <div class="modal" id="delete-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Confirmer la suppression</h3>
                    <button class="close-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.</p>
                    <p id="delete-event-name" class="font-bold"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="cancel-delete">Annuler</button>
                    <button class="btn btn-danger" id="confirm-delete">Supprimer</button>
                </div>
            </div>
        </div>

        <!-- Modal pour voir les inscriptions -->
        <div class="modal" id="registrations-modal">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h3>Inscriptions - <span id="registrations-event-title"></span></h3>
                    <button class="close-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="registrations-stats">
                        <div class="stat-card">
                            <div class="stat-value" id="total-registrations">145</div>
                            <div class="stat-label">Total des inscriptions</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="confirmed-registrations">120</div>
                            <div class="stat-label">Confirmées</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="pending-registrations">25</div>
                            <div class="stat-label">En attente</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="capacity-percentage">72%</div>
                            <div class="stat-label">Capacité remplie</div>
                        </div>
                    </div>
                    <div class="registrations-table-container">
                        <table class="registrations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="registrations-table-body">
                                <tr>
                                    <td>REG-001</td>
                                    <td>Jean Dupont</td>
                                    <td>jean.dupont@example.com</td>
                                    <td>06 12 34 56 78</td>
                                    <td>10/03/2025</td>
                                    <td><span class="registration-status confirmed">Confirmée</span></td>
                                    <td class="actions-cell">
                                        <button class="btn-icon view-registration" data-id="REG-001">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button class="btn-icon delete-registration" data-id="REG-001">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>REG-002</td>
                                    <td>Marie Martin</td>
                                    <td>marie.martin@example.com</td>
                                    <td>07 23 45 67 89</td>
                                    <td>11/03/2025</td>
                                    <td><span class="registration-status confirmed">Confirmée</span></td>
                                    <td class="actions-cell">
                                        <button class="btn-icon view-registration" data-id="REG-002">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button class="btn-icon delete-registration" data-id="REG-002">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>REG-003</td>
                                    <td>Pierre Durand</td>
                                    <td>pierre.durand@example.com</td>
                                    <td>06 98 76 54 32</td>
                                    <td>12/03/2025</td>
                                    <td><span class="registration-status pending">En attente</span></td>
                                    <td class="actions-cell">
                                        <button class="btn-icon view-registration" data-id="REG-003">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button class="btn-icon delete-registration" data-id="REG-003">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="export-options">
                        <button class="btn btn-secondary" id="export-csv">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Exporter en CSV
                        </button>
                        <button class="btn btn-secondary" id="export-pdf">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Exporter en PDF
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="close-registrations">Fermer</button>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-copyright">
                    <p>&copy; 2025 EventHub Admin. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/admin.js"></script>
</body>
</html>
