<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../model/commentairemodel.php');


class CommentaireController

{
    // public function __construct()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         $this->handleRequest();
    //     }
    // }

    public function handleCommentSubmission()
    {
        header('Content-Type: application/json');

        try {
            // Remplacer json_decode par $_POST
            $articleId = filter_var($_POST['article_id'] ?? null, FILTER_VALIDATE_INT);
            $content   = trim($_POST['content'] ?? '');
            $parentId  = isset($_POST['parent_id'])
                ? filter_var($_POST['parent_id'], FILTER_VALIDATE_INT)
                : null;
            $userId    = $_SESSION['user_id'] ?? null; // ← Point critique !
            // Validation (identique)
            if (!$articleId) throw new Exception('ID d\'article invalide');
            if ($content === '') throw new Exception('Le commentaire ne peut pas être vide');
            if (!$userId) throw new Exception('Utilisateur non authentifié');
            $db = Config::getConnexion();
            $stmt = $db->prepare("SELECT nom_user FROM user WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $userId]);
            $user = $stmt->fetch();
            if (!$user) throw new Exception('Utilisateur introuvable');
            $nomUser = $user['nom_user'];
            // Création du commentaire (identique)
            $commentaire = new Commentaire(
                null,
                $articleId,
                $_SESSION['user_id'], // ← Maintenant en position 3 (user_id)
                htmlspecialchars($content),
                date('Y-m-d H:i:s'),
                0,
                $parentId,
                'pending',
                0,
                $nomUser
            );

            if (!$this->ajouterCommentaire($commentaire)) {
                throw new Exception('Erreur de base de données');
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function ajouterCommentaire(Commentaire $c)
    {
        try {
            $db = Config::getConnexion();
            $sql = "INSERT INTO commentaires 
                  (article_id, user_id, auteur, contenu, date_publication, parent_id) 
                VALUES 
                  (:article_id, :user_id, :auteur, :contenu, :date_publication, :parent_id)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':article_id',    $c->getArticleId(),  PDO::PARAM_INT);
            $stmt->bindValue(':user_id',       $c->getUserId(),     PDO::PARAM_INT);
            $stmt->bindValue(':auteur',        $c->getAuteur(),     PDO::PARAM_STR);
            $stmt->bindValue(':contenu',       $c->getContenu(),    PDO::PARAM_STR);
            $stmt->bindValue(':date_publication', $c->getDatePublication());
            $stmt->bindValue(':parent_id',     $c->getParentId(),   PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur d'ajout de commentaire: " . $e->getMessage());
            return false;
        }
    }


    public function getCommentairesByArticle($articleId)
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM commentaires 
                    WHERE article_id = :article_id 
                    ORDER BY date_publication DESC";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':article_id', $articleId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_FUNC, function (
                $id,
                $article_id,
                $auteur,
                $contenu,
                $date_publication,
                $likes,
                $parent_id
            ) {
                return new Commentaire(
                    $id,
                    $article_id,
                    $auteur,
                    $contenu,
                    $date_publication,
                    $likes,
                    $parent_id
                );
            });
        } catch (PDOException $e) {
            error_log("Erreur de récupération des commentaires: " . $e->getMessage());
            return [];
        }
    }

    public function incrementerLikes($commentaireId)
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE commentaires SET likes = likes + 1 WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $commentaireId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur d'incrémentation des likes: " . $e->getMessage());
            return false;
        }
    }
    public function getAllComments($filter = 'all')
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT c.*, u.nom_user as auteur, a.nom_article 
                    FROM commentaires c
                    LEFT JOIN user u ON c.user_id = u.id_user
                    LEFT JOIN article a ON c.article_id = a.ID_Article";

            $where = [];
            $params = [];

            switch ($filter) {
                case 'reported':
                    $where[] = "c.status = 'reported'";
                    break;
                case 'pending':
                    $where[] = "c.status = 'pending'";
                    break;
                case 'approved':
                    $where[] = "c.status = 'approved'";
                    break;
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $sql .= " ORDER BY c.date_publication DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_FUNC, function (
                $id,
                $article_id,
                $auteur,
                $contenu,
                $date_publication,
                $likes,
                $parent_id,
                $status,
                $isRead,
                $articleTitle
            ) {
                return new Commentaire(
                    $id,
                    $article_id,
                    $auteur,
                    $contenu,
                    $date_publication,
                    $likes,
                    $parent_id,
                    $status,
                    $isRead,
                    $articleTitle
                );
            });
        } catch (PDOException $e) {
            error_log("Erreur dans getAllComments(): " . $e->getMessage());
            return [];
        }
    }

    public function getUnreadCommentsCount()
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT COUNT(*) 
                    FROM commentaires 
                    WHERE is_read = 0 
                    AND status IN ('pending', 'reported')";

            return $db->query($sql)->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur dans getUnreadCommentsCount(): " . $e->getMessage());
            return 0;
        }
    }

    public function updateCommentStatus($commentId, $status)
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE commentaires 
                    SET status = :status, 
                        is_read = 1 
                    WHERE id = :id";

            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':id' => $commentId
            ]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateCommentStatus(): " . $e->getMessage());
            return false;
        }
    }

    public function deleteComment($commentId)
    {
        try {
            $db = config::getConnexion();
            $sql = "DELETE FROM commentaires WHERE id = :id";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':id' => $commentId]);
        } catch (PDOException $e) {
            error_log("Erreur dans deleteComment(): " . $e->getMessage());
            return false;
        }
    }
    public function updateCommentContent(int $commentId, string $newContent): bool
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE commentaires 
                    SET contenu = :contenu 
                    WHERE id = :id 
                    AND user_id = :user_id"; // Sécurité supplémentaire

            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':contenu' => $newContent,
                ':id' => $commentId,
                ':user_id' => $_SESSION['user_id']
            ]);
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    public function reportComment(int $commentId): bool
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE commentaires 
                    SET status = 'reported' 
                    WHERE id = :id";

            $stmt = $db->prepare($sql);
            return $stmt->execute([':id' => $commentId]);
        } catch (PDOException $e) {
            error_log("Report error: " . $e->getMessage());
            return false;
        }
    }
    public function handleRequest()
    {
        $action = $_GET['action'] ?? null;
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'delete':
                echo json_encode(['success' => $this->deleteComment($id)]);
                break;

            case 'update':
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode(['success' => $this->updateCommentContent($data['id'], $data['content'])]);
                break;

            case 'report':
                echo json_encode(['success' => $this->reportComment($id)]);
                break;

            default:
                http_response_code(404);
                echo json_encode(['error' => 'Action non reconnue']);
        }
        exit;
    }
}
