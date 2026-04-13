<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../controller/user_controller.php';
require_once '../../controller/articlecontroller.php';
require_once '../../controller/commentairecontroller.php';
$userdhia=new User_controller();
if(isset($_SESSION['user_id'])){

$id_utilisateur = $_SESSION['user_id'];
$profile_user = $userdhia->load_user($id_utilisateur);

}
$userrr=$userdhia->load_user($_SESSION['user_id']);
// $_SESSION['username'] = 'yassine';
$_SESSION['image_profile'] = $userrr->getProfileImage();

$_SESSION['is_admin'] = true;
//$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Ajout du token CSRF

$commentaireController = new CommentaireController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Inclure le contrôleur CommentaireController
  require_once '../../controller/commentairecontroller.php';
  $commentaireController->handleCommentSubmission();
  //header("Location: indexyessine.php");
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
  $selectedArticleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $commentaires = $commentaireController->getCommentairesByArticle($selectedArticle->getId());
  $selectedArticleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$children = [];
$roots = [];

foreach ($commentaires as $commentaire) {
  $parentId = $commentaire->getParentId();
  if ($parentId === null) {
    $roots[] = $commentaire;
  } else {
    $children[$parentId][] = $commentaire;
  }
}
}

function renderComment(Commentaire $c, array $children, int $level = 0, CommentaireController $commentaireController): void
{
  $indentClass = $level > 0 ? 'reply-indent' : '';
  $imageProfile = $commentaireController->getImageProfileByUserId($c->getUserId());
  $imageProfile = $imageProfile ?: 'https://via.placeholder.com/40';
?>
  <div class="comment <?= $indentClass ?>" data-id="<?= $c->getId() ?>">
    <div class="comment-header">
      <img src="<?= htmlspecialchars($imageProfile) ?>" class="comment-avatar" alt="Profile Image" />
      <span class="name"><?= htmlspecialchars($c->getAuteur()) ?></span>

      <div class="comment-actions">
        <button class="btn-like" data-id="<?= $c->getId() ?>">
          <i class="far fa-heart"></i>
          <span class="like-count"><?= $c->getLikes() ?></span>
        </button>

        <?php if ($_SESSION['user_id'] == $c->getUserId() || $_SESSION['is_admin']): ?>
          <button class="btn-modify" title="Modifier" data-id="<?= $c->getId() ?>">
            <i class="fas fa-edit"></i>
          </button>
          <button class="btn-delete" title="Supprimer" data-id="<?= $c->getId() ?>">
            <i class="fas fa-trash"></i>
          </button>
        <?php endif; ?>
        <?php if ($_SESSION['user_id'] == $c->getUserId()): ?>
          <button class="btn-report" title="Signaler" data-id="<?= $c->getId() ?>">
            <i class="fas fa-flag"></i>
          </button>
        <?php endif; ?>

        <button class="btn-reply" title="Répondre" data-id="<?= $c->getId() ?>">
          <i class="fas fa-reply"></i>
        </button>
      </div>
    </div>

    <p><?= parseCommentContent($c->getContenu()) ?></p>
  </div>

  <?php if (!empty($children[$c->getId()])): ?>
    <div class="reply-container">
      <?php foreach ($children[$c->getId()] as $child): ?>
        <?php renderComment($child, $children, $level + 1, $commentaireController); ?>
      <?php endforeach; ?>
    </div>
<?php endif;
}
function parseCommentContent(string $content): string
{
  // Convert [GIF:URL] to images and escape other content
  $content = preg_replace_callback(
    '/\[GIF:(.*?)\]/',
    function ($matches) {
      $url = htmlspecialchars($matches[1], ENT_QUOTES, 'UTF-8');
      return '<img src="' . $url . '" class="comment-gif" alt="User GIF">';
    },
    htmlspecialchars($content, ENT_QUOTES, 'UTF-8')
  );

  // Convert newlines to <br> tags
  return nl2br($content);
}
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
  <link rel="stylesheet" href="stylesyessine.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/theme-custom.css">
  <!-- Emoji Picker Element -->
  <script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js" type="module"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/css/emoji-picker.css">
  <style>
    /* Custom emoji picker styling to match your dark theme */
    emoji-picker {
      --background: var(--color-foreground);
      --border-color: var(--color-border);
      --indicator-color: var(--color-primary);
      --input-border-color: var(--color-border);
      --input-font-color: var(--color-text);
      --input-placeholder-color: var(--color-text-secondary);
      --outline-color: var(--color-primary);
      --category-font-color: var(--color-text);
      --button-hover-background: rgba(255, 255, 255, 0.1);
      --button-active-background: rgba(255, 255, 255, 0.05);
      --skintone-border-color: var(--color-border);
      width: 100%;
      max-height: 300px;
    }
  </style>
</head>


<body>
     <header class="header">
        
        
        <?php if(isset($_SESSION['user_id'])){?>
        <div class="profile-container">
            <div class="profile-icon" id="profileIcon">
                <img src="<?= htmlspecialchars($profile_user->getProfileImage()) ?>" alt="Profile Picture" >
                
            </div>
            <div class="profile-menu" id="profileMenu">
                <div class="profile-header">
                    <div class="name"><?= $profile_user->getName().' '.$profile_user->getLastName(); ?></div>
                    <div class="email"><?= $profile_user->getEmail() ?></div>
                </div>
                <a href="monprofil.php?id=<?= $_SESSION['user_id'] ?>" class="menu-item">
                    <i class="icon-profile"></i>
                    Mon profil
                </a>
                <a href="allUsers.php?id=<?= $_SESSION['user_id'] ?>" class="menu-item">
                    <i class="fas fa-users"></i> <!-- Icône pour plusieurs profils -->
                    Tous les profils
                </a>
                <a href="settings.html" class="menu-item">
                    <i class="icon-settings"></i>
                    Paramètres
                </a>
                <div class="menu-divider"></div>
                <a onclick="destroysession()" href="#" class="menu-item">
                    <i class="icon-logout"></i>
                    Déconnexion
                </a>
            </div>
        </div>
        <?php }?>
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
                        <li><a href="index.php" >Accueil</a></li>
                        <li><a href="index2.php">Evenement</a></li>
                        <li><a href="indexyessine.php" class="active">Articles</a></li>
                        <li><a href="consultationList.php">Consultations</a></li>
                        <?php if (!isset($_SESSION['user_id'])) { ?>
                            <li><a href="login.php"  class="btn btn-primary">Connecter</a></li>
                        <?php } ?>
                    </ul>
                </nav>
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
                  <img src="../backoffice/uploads/<?= htmlspecialchars(basename($article->getImage())) ?>" alt="Image article">
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
                  <img src="../backoffice/uploads/<?= htmlspecialchars(basename($article->getImage())) ?>" alt="Image article">
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
              <img src="../backoffice/uploads/<?= htmlspecialchars(basename($selectedArticle->getImage())) ?>"
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
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Your profile" />
                <div class="form-input">
                  <div class="textarea-container">
                    <textarea placeholder="Ajouter un commentaire..."></textarea>
                    <button id="emojiToggle" type="button">😊</button>
                    <emoji-picker id="emojiPicker" style="display: none;"></emoji-picker>
                    <button id="giphyToggle" type="button">🎉</button>
                    <div id="giphyPicker" style="display: none;">
                      <!-- GIFs will be loaded here -->
                    </div>
                  </div>
                  <button class="btn-primary publish-comment">Publier</button>
                </div>
              </div>



              <div class="comments-list">
                <?php foreach ($roots as $commentaire): ?>
                  <?php renderComment($commentaire, $children, 0, $commentaireController); ?>
                <?php endforeach; ?>
              </div>

              <button class="load-more">Voir plus de commentaires</button>
            </div>
          </div>

          <div class="related-articles">
            <h3>Articles similaires</h3>
            <div class="articles-grid">
              <?php
              if ($selectedArticle) {
                $relatedArticles = $articleController->getRelatedArticles(
                  $selectedArticle->getId(),
                  $selectedArticle->getCategorie()
                );

                foreach ($relatedArticles as $article): ?>
                  <article class="article-card">
                    <div class="article-image">
                      <?php if ($article->getImage()): ?>
                        <img src="../backoffice/uploads/<?= htmlspecialchars(basename($article->getImage())) ?>"
                          alt="<?= htmlspecialchars($article->getNomArticle()) ?>">
                      <?php endif; ?>
                      <span class="category"><?= htmlspecialchars($article->getCategorie()) ?></span>
                    </div>
                    <div class="article-content">
                      <h4>
                        <a href="?id=<?= $article->getId() ?>">
                          <?= htmlspecialchars($article->getNomArticle()) ?>
                        </a>
                      </h4>
                      <div class="article-meta">
                        <div class="author">
                          <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Author">
                          <span></span>
                        </div>
                        <div class="stats">
                          <span><i class="fa-regular fa-eye"></i> <?= $article->getViews() ?></span>
                          <span><i class="fa-regular fa-comment"></i> <?= $articleController->getCommentCount($article->getId()) ?></span>
                        </div>
                      </div>
                    </div>
                  </article>
              <?php endforeach;
              } ?>
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



  <script src="scriptyessine.js"></script>
</body>

</html>