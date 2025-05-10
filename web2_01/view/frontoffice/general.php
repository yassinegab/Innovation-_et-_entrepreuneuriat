<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EntrepreHub - Empowering Entrepreneurs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="produittt/view/frontoffice/projects-front.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        /* Styles de base pour la sidebar */
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --sidebar-bg: #1a1a2e;
            --sidebar-color: #e6e6e6;
            --sidebar-hover: #2a2a40;
            --sidebar-active: #3a3a50;
            --sidebar-icon-size: 20px;
            --header-height: 60px;
            --transition-speed: 0.3s;
        }

        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-color);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: width var(--transition-speed) ease;
            overflow-x: hidden;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            height: var(--header-height);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--sidebar-color);
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-logo-icon {
            min-width: var(--sidebar-icon-size);
            height: var(--sidebar-icon-size);
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--sidebar-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .sidebar-toggle:hover {
            background-color: var(--sidebar-hover);
        }

        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu-item {
            margin: 0;
            padding: 0;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--sidebar-color);
            text-decoration: none;
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .sidebar-menu-link:hover {
            background-color: var(--sidebar-hover);
        }

        .sidebar-menu-link.active {
            background-color: var(--sidebar-active);
            font-weight: 600;
        }

        .sidebar-menu-icon {
            min-width: var(--sidebar-icon-size);
            height: var(--sidebar-icon-size);
        }

        .sidebar-menu-text {
            transition: opacity var(--transition-speed);
            opacity: 1;
        }

        .sidebar.collapsed .sidebar-menu-text,
        .sidebar.collapsed .sidebar-logo-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .sidebar-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 0.5rem 1rem;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--sidebar-hover);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .sidebar-user-info {
            transition: opacity var(--transition-speed);
            opacity: 1;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-user-info {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
        }

        .sidebar.collapsed ~ .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar.collapsed ~ .main-wrapper {
                margin-left: 0;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.active {
                display: block;
            }

            .mobile-sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: none;
                border: none;
                color: inherit;
                cursor: pointer;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 998;
                border-radius: 4px;
                background-color: rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-logo">
                <svg class="sidebar-logo-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <span class="sidebar-logo-text">EntrepreHub</span>
            </a>
            <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            </button>
        </div>
        <nav>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="#home" class="sidebar-menu-link active">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span class="sidebar-menu-text">Accueil</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#dashboard" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span class="sidebar-menu-text">Tableau de bord</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                <a href="/intg/produittt/view/frontoffice/projects-front.php" class="sidebar-menu-link">


                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        <span class="sidebar-menu-text">Projets</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#services" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span class="sidebar-menu-text">Services</span>
                    </a>
                </li>
                <div class="sidebar-divider"></div>
                <li class="sidebar-menu-item">
                    <a href="#community" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="sidebar-menu-text">Communauté</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#resources" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="sidebar-menu-text">Ressources</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#events" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="sidebar-menu-text">Événements</span>
                    </a>
                </li>
                <div class="sidebar-divider"></div>
                <li class="sidebar-menu-item">
                    <a href="#about" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span class="sidebar-menu-text">À propos</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#contact" class="sidebar-menu-link">
                        <svg class="sidebar-menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        <span class="sidebar-menu-text">Contact</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <span>JD</span>
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">Jean Dupont</div>
                    <div class="sidebar-user-role">Entrepreneur</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar Toggle -->
    <button class="mobile-sidebar-toggle" id="mobile-sidebar-toggle" aria-label="Open Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobile-overlay"></div>

    <!-- Main Content -->
    <div class="main-wrapper">
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
                            <li><a href="#home" class="active">Accueil</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#about">À propos</a></li>
                            <li><a href="#testimonials">Témoignages</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li><a href="#" class="btn btn-primary">Commencer</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <section id="home" class="hero">
                <div class="container">
                    <div class="hero-content">
                        <h1>Transformez vos idées en entreprises prospères</h1>
                        <p>Plateforme complète pour les entrepreneurs avec des ressources, des outils et une communauté pour vous aider à réussir.</p>
                        <div class="hero-buttons">
                            <a href="#" class="btn btn-primary">Commencer gratuitement</a>
                            <a href="#" class="btn btn-secondary">En savoir plus</a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat">
                                <span class="stat-number">10k+</span>
                                <span class="stat-label">Entrepreneurs</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number">5k+</span>
                                <span class="stat-label">Startups lancées</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number">95%</span>
                                <span class="stat-label">Taux de satisfaction</span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-image">
                        <div class="code-window">
                            <div class="code-header">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                                <span class="code-title">success.js</span>
                            </div>
                            <div class="code-content">
                                <pre><code>class Entrepreneur {
  constructor(vision, passion) {
    this.vision = vision;
    this.passion = passion;
    this.resources = [];
  }

  addResource(resource) {
    this.resources.push(resource);
    console.log("Growing stronger!");
  }

  launch() {
    if (this.resources.length > 0) {
      return "Success!";
    }
    return "Need more resources";
  }
}

// Create your success story
const myBusiness = new Entrepreneur(
  "Change the world",
  "Unlimited"
);

// EntrepreHub provides resources
myBusiness.addResource("Mentorship");
myBusiness.addResource("Funding");
myBusiness.addResource("Network");

// Launch your business
console.log(myBusiness.launch());</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="services" class="services">
                <div class="container">
                    <div class="section-header">
                        <h2>Nos Services</h2>
                        <p>Tout ce dont vous avez besoin pour développer votre entreprise</p>
                    </div>
                    <div class="services-grid">
                        <div class="service-card">
                            <div class="service-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5"></path>
                                    <path d="M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <h3>Formation d'entreprise</h3>
                            <p>Apprenez les bases de la création d'entreprise avec nos cours en ligne et nos ateliers interactifs.</p>
                            <a href="#" class="link-arrow">En savoir plus</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                </svg>
                            </div>
                            <h3>Mentorat</h3>
                            <p>Connectez-vous avec des entrepreneurs expérimentés qui vous guideront dans votre parcours.</p>
                            <a href="#" class="link-arrow">En savoir plus</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <h3>Réseautage</h3>
                            <p>Rejoignez notre communauté d'entrepreneurs et établissez des connexions précieuses.</p>
                            <a href="#" class="link-arrow">En savoir plus</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <h3>Financement</h3>
                            <p>Accédez à des opportunités de financement et connectez-vous avec des investisseurs potentiels.</p>
                            <a href="#" class="link-arrow">En savoir plus</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Autres sections inchangées -->
            <section id="about" class="about">
                <!-- Contenu inchangé -->
            </section>

            <section id="testimonials" class="testimonials">
                <!-- Contenu inchangé -->
            </section>

            <section id="contact" class="contact">
                <!-- Contenu inchangé -->
            </section>

            <section class="cta">
                <!-- Contenu inchangé -->
            </section>
        </main>

        <footer class="footer">
            <!-- Contenu inchangé -->
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
            
            // Mobile sidebar toggle
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            const mobileOverlay = document.getElementById('mobile-overlay');
            
            mobileSidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                mobileOverlay.classList.toggle('active');
            });
            
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            });
            
            // Sidebar menu links
            const sidebarMenuLinks = document.querySelectorAll('.sidebar-menu-link');
            
            sidebarMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Remove active class from all links
                    sidebarMenuLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Close mobile sidebar after clicking a link
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('mobile-open');
                        mobileOverlay.classList.remove('active');
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>