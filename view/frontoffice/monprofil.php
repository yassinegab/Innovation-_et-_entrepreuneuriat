<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="assets/monprofil.css">
    </head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">ProfilApp</a>
                <div class="nav-links">
                    <a href="index.php">Accueil</a>
                    
                </div>
                <div class="profile-icon">
                    <img src="assets/398582438_2615487838627907_5927319269485945046_n.jpg" alt="Photo de profil">
                </div>
                <div class="mobile-menu-btn">☰</div>
            </nav>
        </div>
    </header>

    <section class="profile-header">
        <div class="container">
            <div class="profile-container">
                <div class="profile-image fade-in">
                    <img src="assets/398582438_2615487838627907_5927319269485945046_n.jpg" alt="Photo de profil">
                </div>
                <h1 class="profile-name fade-in delay-1">Moez Touil</h1>
                <p class="profile-title fade-in delay-1">Développeur Web</p>
                <p class="profile-bio fade-in delay-2">Passionné par le développement web et les nouvelles technologies. Je crée des expériences utilisateur innovantes et intuitives depuis plus de 5 ans.</p>
                
                <div class="profile-stats fade-in delay-2">
                    <div class="stat">
                        <div class="stat-value">125</div>
                        <div class="stat-label">Publications</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">1.2K</div>
                        <div class="stat-label">Abonnés</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">348</div>
                        <div class="stat-label">Abonnements</div>
                    </div>
                </div>
                
                <div class="profile-actions fade-in delay-3">
                    <a href="#" class="btn btn-primary"><span class="btn-icon">✏️</span>Modifier le profil</a>
                    <a href="collaborations.php" class="btn btn-outline"><span class="btn-icon">👥</span>Mes collaborations</a>
                    <a href="#" class="btn btn-secondary"><span class="btn-icon">🔗</span>Partager</a>
                </div>
            </div>
        </div>
    </section>

    <main class="main-content container">
        <section class="section fade-in delay-3">
            <h2 class="section-title">À propos</h2>
            <div class="about-grid">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">thomas.durand@example.com</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">+33 6 12 34 56 78</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Localisation</div>
                    <div class="info-value">Paris, France</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Site Web</div>
                    <div class="info-value">www.thomasdurand.fr</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Membre depuis</div>
                    <div class="info-value">Janvier 2020</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Entreprise</div>
                    <div class="info-value">TechInnovate</div>
                </div>
            </div>
        </section>

        <section class="section fade-in delay-4">
            <h2 class="section-title">Compétences</h2>
            <div class="skills-container">
                <span class="skill-tag">HTML5</span>
                <span class="skill-tag">CSS3</span>
                <span class="skill-tag">JavaScript</span>
                <span class="skill-tag">React</span>
                <span class="skill-tag">Node.js</span>
                <span class="skill-tag">Vue.js</span>
                <span class="skill-tag">TypeScript</span>
                <span class="skill-tag">MongoDB</span>
                <span class="skill-tag">GraphQL</span>
                <span class="skill-tag">UI/UX Design</span>
                <span class="skill-tag">Responsive Design</span>
                <span class="skill-tag">Git</span>
            </div>
        </section>

        <section class="section fade-in delay-4">
            <h2 class="section-title">Activités récentes</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">📝</div>
                    <div class="activity-content">
                        <div class="activity-title">A publié un nouvel article : "Les tendances du développement web en 2025"</div>
                        <div class="activity-time">Il y a 2 jours</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">🏆</div>
                    <div class="activity-content">
                        <div class="activity-title">A terminé un nouveau projet : "Application de gestion de tâches"</div>
                        <div class="activity-time">Il y a 1 semaine</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">👥</div>
                    <div class="activity-content">
                        <div class="activity-title">A démarré une nouvelle collaboration avec Studio Design sur le projet "E-commerce Premium"</div>
                        <div class="activity-time">Il y a 10 jours</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">👍</div>
                    <div class="activity-content">
                        <div class="activity-title">A aimé le projet de Marie Dupont : "Portfolio interactif"</div>
                        <div class="activity-time">Il y a 2 semaines</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">💬</div>
                    <div class="activity-content">
                        <div class="activity-title">A commenté sur l'article : "L'avenir de l'IA dans le développement"</div>
                        <div class="activity-time">Il y a 3 semaines</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="social-links">
                <a href="#" class="social-link">𝕏</a>
                <a href="#" class="social-link">in</a>
                <a href="#" class="social-link">f</a>
                <a href="#" class="social-link">🐙</a>
            </div>
            <div class="footer-links">
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Politique de confidentialité</a>
                <a href="#">Aide</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; 2025 ProfilApp. Tous droits réservés.
            </div>
        </div>
    </footer>
    <script src="assets/monprofil.js"></script>
</body>
</html>    