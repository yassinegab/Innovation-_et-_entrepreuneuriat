<?php
require_once __DIR__ . '/../../controller/evenController.php';
require_once __DIR__ . '/../../model/evenmodel.php';

// Récupération des événements
$evenementcontroller = new EvenementController();
$list = $evenementcontroller->listeven(); // Utilisation de la méthode du contrôleur

// Récupération des catégories pour le filtre
$categories = $evenementcontroller->getAllCategories();

// Traitement des filtres
$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$filterUpcoming = isset($_GET['upcoming']) ? (int)$_GET['upcoming'] : null;

// Appliquer les filtres si demandé
if (!empty($searchKeyword)) {
    $list = $evenementcontroller->searchEvents($searchKeyword);
} elseif (!empty($filterCategory)) {
    $list = $evenementcontroller->filterByCategory($filterCategory);
}

// Fonction pour formater la date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d/m/Y');
}

// Fonction pour obtenir le jour de la semaine en français
function getJourSemaine($date) {
    $jours = [
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    ];
    
    $dateObj = new DateTime($date);
    $jourEn = $dateObj->format('l');
    return $jours[$jourEn];
}

// Fonction pour vérifier si un événement est à venir
function isUpcoming($date) {
    $eventDate = new DateTime($date);
    $today = new DateTime();
    return $eventDate >= $today;
}

// Trier les événements: événements à venir d'abord, puis par date
usort($list, function($a, $b) {
    $dateA = new DateTime($a['date']);
    $dateB = new DateTime($b['date']);
    $today = new DateTime();
    
    $aUpcoming = $dateA >= $today;
    $bUpcoming = $dateB >= $today;
    
    if ($aUpcoming && !$bUpcoming) return -1;
    if (!$aUpcoming && $bUpcoming) return 1;
    
    return $dateA <=> $dateB;
});

// Filtrer les événements à venir si demandé
if ($filterUpcoming === 1) {
    $list = array_filter($list, function($event) {
        return isUpcoming($event['date']);
    });
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
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Inter', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            background-color: #1a1a1a;
            color: white;
            padding: 1rem 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #f0c040;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1.5rem;
        }
        
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .nav-menu a:hover {
            color: #f0c040;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #f0c040;
            color: #1a1a1a;
        }
        
        .btn-primary:hover {
            background-color: #e0b030;
        }
        
        .services {
            padding: 4rem 0;
            background-color: #121212;
            color: #ffffff;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .section-header h2 {
            font-size: 2rem;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }
        
        .section-header p {
            color: #cccccc;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Styles pour la section des événements */
        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .events-count {
            font-size: 0.9rem;
            color: #aaaaaa;
        }
        
        .events-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .events-search {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        
        .events-search input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        .events-search svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .events-filter select {
            padding: 0.75rem 2rem 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1.5em 1.5em;
        }
        
        .events-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .toggle-label {
            font-size: 0.875rem;
            color: #cccccc;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 3rem;
            height: 1.5rem;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e5e7eb;
            transition: .4s;
            border-radius: 1.5rem;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 1.1rem;
            width: 1.1rem;
            left: 0.2rem;
            bottom: 0.2rem;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: #10b981;
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(1.5rem);
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .event-card {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(255, 255, 255, 0.1), 0 2px 4px -1px rgba(255, 255, 255, 0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #1e1e1e;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .event-card-header {
            position: relative;
            padding: 1.25rem;
            background-color: #252525;
            border-bottom: 1px solid #333333;
        }
        
        .event-date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .event-date svg {
            color: #6b7280;
            width: 1rem;
            height: 1rem;
        }
        
        .event-date-text {
            font-size: 0.875rem;
            color: #cccccc;
        }
        
        .event-date-day {
            font-weight: 600;
        }
        
        .event-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .event-category {
            display: inline-block;
            background-color: #333333;
            color: #cccccc;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-weight: 500;
        }
        
        .event-card-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .event-info {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .event-info svg {
            flex-shrink: 0;
            width: 1rem;
            height: 1rem;
            color: #6b7280;
            margin-top: 0.125rem;
        }
        
        .event-info-text {
            font-size: 0.875rem;
            color: #cccccc;
        }
        
        .event-card-footer {
            padding: 1.25rem;
            border-top: 1px solid #333333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-price {
            font-weight: 600;
            color: #111827;
        }
        
        .event-price.free {
            color: #10b981;
        }
        
        .event-features {
            display: flex;
            gap: 0.75rem;
        }
        
        .event-feature {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .feature-icon {
            width: 1rem;
            height: 1rem;
        }
        
        .feature-icon.yes {
            color: #10b981;
        }
        
        .feature-icon.no {
            color: #ef4444;
        }
        
        .feature-text {
            font-size: 0.75rem;
            color: #aaaaaa;
        }
        
        .past-event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        
        .past-event-label {
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .events-empty {
            text-align: center;
            padding: 3rem 1rem;
            background-color: #1e1e1e;
            border-radius: 0.5rem;
            color: #aaaaaa;
        }
        
        .btn-register {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #f0c040;
            color: #1a1a1a;
            text-align: center;
            font-weight: 600;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s;
            margin-top: 1rem;
        }
        
        .btn-register:hover {
            background-color: #e0b030;
        }

        .wanna-subscribe-btn {
            display: block;
            width: 250px;
            margin: 2rem auto;
            padding: 1rem 2rem;
            background-color: #f0c040;
            color: #000000;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .wanna-subscribe-btn:hover {
            background-color: #e0b030;
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .events-filters {
                flex-direction: column;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                <a href="index_insc.php" class="wanna-subscribe-btn">Wanna subscribe?</a>
                
                <div class="events-header">
                    <div class="events-count">
                        <?php echo count($list); ?> événement<?php echo count($list) > 1 ? 's' : ''; ?> disponible<?php echo count($list) > 1 ? 's' : ''; ?>
                    </div>
                </div>
                
                <!-- Filtres et recherche -->
                <div class="events-filters">
                    <form action="" method="GET" class="events-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($searchKeyword) ?>">
                    </form>
                    
                    <div class="events-filter">
                        <form action="" method="GET">
                            <select name="category" onchange="this.form.submit()">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category) ?>" <?= $filterCategory == $category ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="events-toggle">
                        <span class="toggle-label">Événements à venir uniquement</span>
                        <label class="toggle-switch">
                            <input type="checkbox" id="upcoming-toggle" <?= $filterUpcoming === 1 ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <!-- Affichage des événements en grille de cartes -->
                <?php if (empty($list)): ?>
                    <div class="events-empty">
                        <p>Aucun événement n'est actuellement programmé. Revenez bientôt pour découvrir nos prochains événements!</p>
                    </div>
                <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($list as $even): ?>
                            <?php $isUpcoming = isUpcoming($even['date']); ?>
                            <div class="event-card">
                                <?php if (!$isUpcoming): ?>
                                    <div class="past-event-overlay">
                                        <span class="past-event-label">Événement passé</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="event-card-header">
                                    <div class="event-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="event-date-text">
                                            <span class="event-date-day"><?= getJourSemaine($even['date']); ?></span>
                                            <?= formatDate($even['date']); ?>
                                        </span>
                                    </div>
                                    
                                    <h3 class="event-name"><?= htmlspecialchars($even['nom']); ?></h3>
                                    
                                    <?php if (isset($even['categorie']) && !empty($even['categorie'])): ?>
                                        <div class="event-category"><?= htmlspecialchars($even['categorie']); ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="event-card-body">
                                    <div class="event-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="event-info-text"><?= htmlspecialchars($even['lieu']); ?></span>
                                    </div>
                                    
                                    <div class="event-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="event-info-text"><?= htmlspecialchars($even['capacite']); ?> places disponibles</span>
                                    </div>
                                    
                                    <div class="event-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="event-info-text">
                                            <?php if (isset($even['prix']) && $even['prix'] > 0): ?>
                                                <span class="event-price"><?= number_format((float)$even['prix'], 3, ',', ' '); ?> TND</span>
                                            <?php else: ?>
                                                <span class="event-price free">Gratuit</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    
                                    
                                </div>
                                
                                <div class="event-card-footer">
                                    <div class="event-features">
                                        <div class="event-feature">
                                            <?php if (isset($even['espace_fumeur']) && $even['espace_fumeur']): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="feature-icon yes" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="feature-icon no" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            <?php endif; ?>
                                            <span class="feature-text">Espace fumeur</span>
                                        </div>
                                        
                                        <div class="event-feature">
                                            <?php if (isset($even['accompagnateur_autorise']) && $even['accompagnateur_autorise']): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="feature-icon yes" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="feature-icon no" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            <?php endif; ?>
                                            <span class="feature-text">Accompagnateur</span>
                                        </div>
                                    </div>
                                    
                                    <div class="event-id">
                                        <span class="feature-text">ID: <?= htmlspecialchars($even['id_ev']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

    <script>
        // Script pour le toggle des événements à venir
        document.addEventListener('DOMContentLoaded', function() {
            const upcomingToggle = document.getElementById('upcoming-toggle');
            
            upcomingToggle.addEventListener('change', function() {
                const currentUrl = new URL(window.location.href);
                
                if (this.checked) {
                    currentUrl.searchParams.set('upcoming', '1');
                } else {
                    currentUrl.searchParams.delete('upcoming');
                }
                
                window.location.href = currentUrl.toString();
            });
        });
    </script>
</body>
</html>
