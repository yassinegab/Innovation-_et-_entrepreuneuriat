<?php
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';

// Récupération des événements
$evenementcontroller = new EvenementController();
$db = config::getConnexion();
$query = $db->prepare("SELECT * FROM evenement");
$query->execute();
$list = $query->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour formater la date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d/m/Y');
}
?>
<!DOCTYPE html>
<html lang="fr">
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
        <!-- Section Hero existante -->
        <section id="home" class="hero">
            <!-- Contenu existant -->
        </section>

        <!-- Section des événements améliorée -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-header">
                    <h2>Nos Événements</h2>
                    <p>Découvrez nos prochains événements pour développer votre entreprise</p>
                </div>
                
                <div class="events-header">
                    <div class="events-count">
                        <?php echo count($list); ?> événement<?php echo count($list) > 1 ? 's' : ''; ?> disponible<?php echo count($list) > 1 ? 's' : ''; ?>
                    </div>
                </div>
                
                <div class="events-table-container">
                    <?php if (empty($list)): ?>
                        <div class="events-empty">
                            <p>Aucun événement n'est actuellement programmé. Revenez bientôt pour découvrir nos prochains événements!</p>
                        </div>
                    <?php else: ?>
                        <table class="events-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom de l'événement</th>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Capacité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list as $even): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($even['id_ev']); ?></td>
                                        <td class="event-name"><?= htmlspecialchars($even['nom']); ?></td>
                                        <td class="event-date"><?= isset($even['date']) ? formatDate($even['date']) : 'À déterminer'; ?></td>
                                        <td><?= htmlspecialchars($even['lieu']); ?></td>
                                        <td class="event-capacity"><?= htmlspecialchars($even['capacite']); ?> places</td>
                                        <td class="event-actions">
                                            <a href="evenement.php?id=<?= $even['id_ev']; ?>" class="event-action-btn btn-view">
                                                Détails
                                            </a>
                                            <a href="evenement.php?id=<?= $even['id_ev']; ?>#inscription" class="event-action-btn btn-register">
                                                S'inscrire
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Autres sections existantes -->
        <section id="about" class="about">
            <!-- Contenu existant -->
        </section>

        <section id="testimonials" class="testimonials">
            <!-- Contenu existant -->
        </section>

        <section id="contact" class="contact">
            <!-- Contenu existant -->
        </section>

        <section class="cta">
            <!-- Contenu existant -->
        </section>
    </main>

    <footer class="footer">
        <!-- Contenu existant -->
    </footer>

    <script src="script.js"></script>
</body>
</html>