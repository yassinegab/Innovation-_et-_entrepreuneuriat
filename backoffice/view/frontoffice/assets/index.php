<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Configuration de test
$_SESSION['user_id'] = 1;
$_SESSION['is_admin'] = true;
//$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Ajout du token CSRF
require_once '../../../controller/articlecontroller.php';
require_once '../../../controller/commentairecontroller.php';
$commentaireController = new CommentaireController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Inclure le contrôleur CommentaireController
  require_once '../../../controller/commentairecontroller.php';
  $commentaireController->handleCommentSubmission();
  exit; // Empêcher l'exécution de tout le reste du code si la requête est POST
}


$articleController = new ArticleController();

// Récupération des articles
$trendingArticles = $articleController->getTrendingArticles(3);
$recentArticles = $articleController->getRecentArticles(5);

// Article sélectionné
$selectedArticle = null;
if (isset($_GET['id'])) {
  $selectedArticle = $articleController->getArticleById($_GET['id']);
  $articleController->incrementViews($_GET['id']);
}
$selectedArticleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
function timeAgo($datetime)
{
  $time = strtotime($datetime);
  $diff = time() - $time;

  $intervals = [
    31536000 => 'an',
    2592000 => 'mois',
    604800 => 'semaine',
    86400 => 'jour',
    3600 => 'heure',
    60 => 'minute'
  ];

  foreach ($intervals as $secs => $text) {
    $div = $diff / $secs;
    if ($div >= 1) {
      $num = round($div);
      return "Il y a $num $text" . ($num > 1 ? 's' : '');
    }
  }
  return 'À l\'instant';
}
?>













<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>EntrepreneuHub - Plateforme de blogs pour entrepreneurs</title>
  <link rel="stylesheet" href="styles.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>


<body>
  <header>
    <div class="container header-container">
      <div class="logo">
        <i class="fa-solid fa-lightbulb"></i>
        <h1>EntrepreneuHub</h1>
      </div>
      <nav>
        <ul>
          <li><a href="#" class="active">Accueil</a></li>
          <li><a href="#">Articles</a></li>
          <li><a href="#">Catégories</a></li>
          <li><a href="#">À propos</a></li>
        </ul>
      </nav>
      <div class="header-actions">
        <div class="search-bar">
          <input type="text" placeholder="Rechercher..." />
          <button><i class="fa-solid fa-search"></i></button>
        </div>
        <button class="btn-primary">
          <i class="fa-solid fa-plus"></i> Publier
        </button>
        <div class="profile-menu">
          <img
            src="https://randomuser.me/api/portraits/men/32.jpg"
            alt="Photo de profil" />
          <div class="dropdown-menu">
            <a href="#">Mon profil</a>
            <a href="#">Mes articles</a>
            <a href="#">Paramètres</a>
            <a href="#">Déconnexion</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h2>Partagez vos idées et expériences entrepreneuriales</h2>
          <p>
            Rejoignez notre communauté d'entrepreneurs passionnés et partagez
            vos connaissances
          </p>
          <button class="btn-primary">Commencer à écrire</button>
        </div>
        <div class="hero-image">
          <img
            src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80"
            alt="Entrepreneurs travaillant ensemble" />
        </div>
      </div>
    </section>

    <section class="trending">
      <div class="container">
        <div class="section-header">
          <h3>Articles tendance</h3>
          <a href="#" class="view-all">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="articles-grid">
          <?php foreach ($trendingArticles as $article): ?>
            <article class="article-card <?= $article->getViews() > 1000 ? 'featured' : '' ?>">
              <div class="article-image">
                <?php if ($article->getImage()): ?>
                  <!-- Correction du chemin d'accès aux images -->
                  <img src="../../backoffice/uploads/<?= htmlspecialchars(basename($article->getImage())) ?>" alt="Image article">
                <?php endif; ?>
                <span class="category"><?= htmlspecialchars($article->getCategorie()) ?></span>
              </div>
              <div class="article-content">
                <a href="?id=<?= $article->getId() ?>">
                  <?= htmlspecialchars($article->getNomArticle()) ?>
                </a>
                <p><?= $article->getExcerpt(150) ?></p>
                <div class="article-meta">
                  <div class="author">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Auteur">
                    <span></span>
                  </div>
                  <div class="stats">
                    <span><i class="fa-regular fa-eye"></i> <?= $article->getViews() ?></span>
                    <span><i class="fa-regular fa-comment"></i> 24</span>
                    <span><i class="fa-regular fa-bookmark"></i></span>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="latest">
      <div class="container">
        <div class="section-header">
          <h3>Articles récents</h3>
          <a href="#" class="view-all">Voir tout <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="articles-list">
          <?php foreach ($recentArticles as $article): ?>
            <article class="article-list-item">
              <div class="article-list-content">
                <div class="article-list-meta">
                  <span class="category"><?= htmlspecialchars($article->getCategorie()) ?></span>
                  <span class="date"><?= timeAgo($article->getDateSoumission()) ?></span>
                </div>
                <h4>
                  <a href="?id=<?= $article->getId() ?>">
                    <?= htmlspecialchars($article->getNomArticle()) ?>
                  </a>
                </h4>
                <p><?= $article->getExcerpt(200) ?></p>
                <div class="article-meta">
                  <div class="author">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Auteur">
                    <span></span>
                  </div>
                  <div class="stats">
                    <span><i class="fa-regular fa-eye"></i> <?= $article->getViews() ?></span>
                    <span><i class="fa-regular fa-comment"></i> 9</span>
                  </div>
                </div>
              </div>
              <?php if ($article->getImage()): ?>
                <div class="article-list-image">
                  <!-- Correction du chemin d'accès -->
                  <img src="../../backoffice/uploads/<?= htmlspecialchars(basename($article->getImage())) ?>" alt="Image article">
                </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <?php if ($selectedArticle): ?>
      <section class="article-detail">
        <div class="container">
          <div class="article-header">
            <h2><?= htmlspecialchars($selectedArticle->getNomArticle()) ?></h2>
            <div class="article-meta-large">
              <div class="author">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Auteur">
                <div>
                  <span class="name"></span>
                  <span class="date">
                    Publié <?= timeAgo($selectedArticle->getDateSoumission()) ?>
                  </span>
                </div>
              </div>
              <div class="actions">
                <button><i class="fa-regular fa-bookmark"></i> Sauvegarder</button>
                <button><i class="fa-solid fa-share-nodes"></i> Partager</button>
              </div>
            </div>
          </div>

          <div class="article-content-full">
            <?php if ($selectedArticle->getImage()): ?>
              <!-- Chemin corrigé avec sécurité -->
              <img src="../../backoffice/uploads/<?= htmlspecialchars(basename($selectedArticle->getImage())) ?>"
                class="article-main-image"
                alt="<?= htmlspecialchars($selectedArticle->getNomArticle()) ?>">
            <?php endif; ?>
            <div class="article-text">
              <?= nl2br(htmlspecialchars($selectedArticle->getContenu())) ?>
            </div>

            <div class="article-comments">
              <script>
                // Ajouter cette ligne pour exposer la variable à JS
                const selectedArticleId = <?= $selectedArticleId ?>;
              </script>

              <div class="comment-form">
                <img
                  src="https://randomuser.me/api/portraits/men/32.jpg"
                  alt="Your profile" />
                <div class="form-input">
                  <textarea placeholder="Ajouter un commentaire..."></textarea>
                  <button class="btn-primary">Publier</button>
                </div>
              </div>

              <div class="comments-list">
                <?php
                $commentaires = $commentaireController->getCommentairesByArticle($selectedArticle->getId());
                foreach ($commentaires as $commentaire):
                ?>
                  <div class="comment" data-id="<?= $commentaire->getId() ?>">
                    <div class="comment-header">
                      <span class="name"><?= htmlspecialchars($commentaire->getAuteur()) ?></span>

                      <div class="comment-actions">
                        <button class="btn-like" data-id="<?= $commentaire->getId() ?>">
                          <i class="far fa-heart"></i>
                          <span class="like-count"><?= $commentaire->getLikes() ?></span>
                        </button>
                        <?php if ($_SESSION['user_id'] == $commentaire->getUserId() || $_SESSION['is_admin']): ?>
                          <button class="btn-modify" title="Modifier" data-id="<?= $commentaire->getId() ?>">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn-delete" title="Supprimer" data-id="<?= $commentaire->getId() ?>">
                            <i class="fas fa-trash"></i>
                          </button>
                        <?php endif; ?>

                        <?php if ($_SESSION['user_id'] != $commentaire->getUserId()): ?>
                          <button class="btn-report" title="Signaler" data-id="<?= $commentaire->getId() ?>">
                            <i class="fas fa-flag"></i>
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                    <p><?= htmlspecialchars($commentaire->getContenu()) ?></p>
                  </div>
                <?php endforeach; ?>
              </div>
              <button class="load-more">Voir plus de commentaires</button>
            </div>
          </div>

          <div class="related-articles">
            <h3>Articles similaires</h3>
            <div class="articles-grid">
              <article class="article-card">
                <div class="article-image">
                  <img
                    src="https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80"
                    alt="Article image" />
                  <span class="category">Finance</span>
                </div>
                <div class="article-content">
                  <h4>
                    Comment valoriser votre startup avant une levée de fonds
                  </h4>
                  <div class="article-meta">
                    <div class="author">
                      <img
                        src="https://randomuser.me/api/portraits/men/42.jpg"
                        alt="Author" />
                      <span>Pierre Durand</span>
                    </div>
                    <div class="stats">
                      <span><i class="fa-regular fa-eye"></i> 945</span>
                      <span><i class="fa-regular fa-comment"></i> 15</span>
                    </div>
                  </div>
                </div>
              </article>

              <article class="article-card">
                <div class="article-image">
                  <img
                    src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80"
                    alt="Article image" />
                  <span class="category">Stratégie</span>
                </div>
                <div class="article-content">
                  <h4>
                    Les alternatives au capital-risque pour financer votre
                    entreprise
                  </h4>
                  <div class="article-meta">
                    <div class="author">
                      <img
                        src="https://randomuser.me/api/portraits/women/22.jpg"
                        alt="Author" />
                      <span>Julie Leroy</span>
                    </div>
                    <div class="stats">
                      <span><i class="fa-regular fa-eye"></i> 723</span>
                      <span><i class="fa-regular fa-comment"></i> 8</span>
                    </div>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-logo">
          <i class="fa-solid fa-lightbulb"></i>
          <h2>EntrepreneuHub</h2>
          <p>La plateforme de partage de connaissances pour entrepreneurs</p>
        </div>
        <div class="footer-links">
          <div class="footer-column">
            <h4>Navigation</h4>
            <ul>
              <li><a href="#">Accueil</a></li>
              <li><a href="#">Articles</a></li>
              <li><a href="#">Catégories</a></li>
              <li><a href="#">À propos</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h4>Catégories</h4>
            <ul>
              <li><a href="#">Stratégie</a></li>
              <li><a href="#">Marketing</a></li>
              <li><a href="#">Finance</a></li>
              <li><a href="#">Leadership</a></li>
              <li><a href="#">Innovation</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h4>Légal</h4>
            <ul>
              <li><a href="#">Conditions d'utilisation</a></li>
              <li><a href="#">Politique de confidentialité</a></li>
              <li><a href="#">Mentions légales</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-newsletter">
          <h4>Restez informé</h4>
          <p>
            Inscrivez-vous à notre newsletter pour recevoir les derniers
            articles et conseils
          </p>
          <div class="newsletter-form">
            <input type="email" placeholder="Votre email" />
            <button class="btn-primary">S'inscrire</button>
          </div>
          <div class="social-links">
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2023 EntrepreneuHub. Tous droits réservés.</p>
      </div>
    </div>
  </footer>



  <script src="script.js"></script>
</body>

</html>