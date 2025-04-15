<?php
require_once __DIR__ . '/../../controller/evenController.php';

require_once __DIR__ . '/../../model/evenmodel.php';
$evenementcontroller = new EvenementController();
$list=$evenementcontroller->listeven();
$db = config::getConnexion();
$query = $db->prepare("SELECT * FROM evenement");
$query->execute();
$list = $query->fetchAll(PDO::FETCH_ASSOC);

// Si tu veux vérifier si $list contient des données
if (empty($list)) {
    echo "Aucun événement trouvé.";
}

if (empty($list)) {
    echo "Aucun événement trouvé.";
}
else {
    var_dump($list); // Afficher le contenu de $list
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EntrepreHub - Empowering Entrepreneurs</title>
    <link rel="stylesheet" href="style.css">
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
                    <h2>Nos evenement</h2>
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
                        <section id="services" class="services">
                        <div class="container">
                         <div class="section-header">

                         <table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Date</th>
            <th>Lieu</th>
            <th>Capacité</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $even) { ?>
            <tr>
                <td><?= htmlspecialchars($even['id_ev']); ?></td>
                <td><?= htmlspecialchars($even['nom']); ?></td>
                <td><?= htmlspecialchars($even['date']); ?></td>
                <td><?= htmlspecialchars($even['lieu']); ?></td>
                <td><?= htmlspecialchars($even['capacite']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>



                   
                        
                </div>
            </div>
        </section>

        <section id="about" class="about">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <div class="section-header">
                            <h2>À propos de nous</h2>
                            <p>Votre partenaire dans le voyage entrepreneurial</p>
                        </div>
                        <p>EntrepreHub a été fondé en 2020 avec une mission claire : démocratiser l'entrepreneuriat et rendre les ressources d'affaires accessibles à tous. Notre plateforme combine technologie, expertise et communauté pour aider les entrepreneurs à chaque étape de leur parcours.</p>
                        <p>Que vous soyez au stade de l'idée ou que vous cherchiez à développer votre entreprise existante, nous avons les outils et le soutien dont vous avez besoin pour réussir.</p>
                        <div class="about-features">
                            <div class="feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Plateforme tout-en-un</span>
                            </div>
                            <div class="feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Experts de l'industrie</span>
                            </div>
                            <div class="feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Communauté mondiale</span>
                            </div>
                            <div class="feature">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Ressources personnalisées</span>
                            </div>
                        </div>
                    </div>
                    <div class="about-image">
                        <div class="stats-card">
                            <div class="stats-header">
                                <h3>Notre impact</h3>
                            </div>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-value">10,000+</div>
                                    <div class="stat-label">Entrepreneurs</div>
                                    <div class="stat-bar">
                                        <div class="stat-bar-fill" style="width: 85%;"></div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">5,000+</div>
                                    <div class="stat-label">Entreprises lancées</div>
                                    <div class="stat-bar">
                                        <div class="stat-bar-fill" style="width: 65%;"></div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">€25M+</div>
                                    <div class="stat-label">Financement obtenu</div>
                                    <div class="stat-bar">
                                        <div class="stat-bar-fill" style="width: 75%;"></div>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">30+</div>
                                    <div class="stat-label">Pays</div>
                                    <div class="stat-bar">
                                        <div class="stat-bar-fill" style="width: 45%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="testimonials">
            <div class="container">
                <div class="section-header">
                    <h2>Ce que disent nos entrepreneurs</h2>
                    <p>Découvrez comment EntrepreHub a aidé des entrepreneurs comme vous</p>
                </div>
                <div class="testimonial-slider">
                    <div class="testimonial-track">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"EntrepreHub a complètement transformé mon parcours entrepreneurial. Les ressources et le mentorat que j'ai reçus ont été inestimables pour le lancement de ma startup."</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <img src="/placeholder.svg?height=50&width=50" alt="Sophie Martin">
                                </div>
                                <div class="author-info">
                                    <h4>Sophie Martin</h4>
                                    <p>Fondatrice, TechSolutions</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"Grâce à la communauté EntrepreHub, j'ai pu connecter avec des investisseurs qui croyaient en ma vision. Maintenant, mon entreprise est en pleine croissance."</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <img src="/placeholder.svg?height=50&width=50" alt="Thomas Dubois">
                                </div>
                                <div class="author-info">
                                    <h4>Thomas Dubois</h4>
                                    <p>CEO, GreenInnovate</p>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <p>"Les ateliers et les cours en ligne d'EntrepreHub m'ont donné les compétences dont j'avais besoin pour transformer mon idée en une entreprise rentable."</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <img src="/placeholder.svg?height=50&width=50" alt="Camille Bernard">
                                </div>
                                <div class="author-info">
                                    <h4>Camille Bernard</h4>
                                    <p>Fondatrice, ArtisanMarket</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-nav">
                        <button class="nav-prev" aria-label="Previous testimonial">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <div class="nav-dots">
                            <button class="nav-dot active" aria-label="Testimonial 1"></button>
                            <button class="nav-dot" aria-label="Testimonial 2"></button>
                            <button class="nav-dot" aria-label="Testimonial 3"></button>
                        </div>
                        <button class="nav-next" aria-label="Next testimonial">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="contact">
            <div class="container">
                <div class="contact-content">
                    <div class="contact-info">
                        <div class="section-header">
                            <h2>Contactez-nous</h2>
                            <p>Prêt à commencer votre voyage entrepreneurial?</p>
                        </div>
                        <p>Que vous ayez des questions sur nos services ou que vous souhaitiez en savoir plus sur la façon dont nous pouvons vous aider, notre équipe est là pour vous.</p>
                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="method-details">
                                    <h4>Email</h4>
                                    <p>contact@entreprehub.com</p>
                                </div>
                            </div>
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="method-details">
                                    <h4>Téléphone</h4>
                                    <p>+33 1 23 45 67 89</p>
                                </div>
                            </div>
                            <div class="contact-method">
                                <div class="method-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="method-details">
                                    <h4>Adresse</h4>
                                    <p>123 Avenue de l'Innovation, 75001 Paris</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="contact-form-container">
                        <form class="contact-form">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Sujet</label>
                                <select id="subject" name="subject">
                                    <option value="general">Question générale</option>
                                    <option value="mentorship">Mentorat</option>
                                    <option value="funding">Financement</option>
                                    <option value="partnership">Partenariat</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Prêt à transformer votre vision en réalité?</h2>
                    <p>Rejoignez des milliers d'entrepreneurs qui ont déjà commencé leur voyage avec EntrepreHub.</p>
                    <a href="#" class="btn btn-primary">Commencer gratuitement</a>
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
                            <li><a href="#">Carrières</a></li>
                            <li><a href="#">Partenaires</a></li>
                        </ul>
                    </div>
                    <div class="footer-links-column">
                        <h3>Ressources</h3>
                        <ul>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Guides</a></li>
                            <li><a href="#">Événements</a></li>
                            <li><a href="#">FAQ</a></li>
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
</body>
</html>
