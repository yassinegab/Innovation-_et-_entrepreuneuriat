
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Inscriptions - EntrepreHub</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
   

  
        
    
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>EntrepreHub</h2>
                <span class="admin-badge">Admin</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item">
                        <a href="dashboard.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="formulaire-evenement.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Événements</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="formulaire-inscription.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Inscriptions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#speakers">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Intervenants</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#analytics">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            <span>Analytiques</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span>Messages</span>
                            <span class="notification-badge">5</span>
                        </a>
                    </li>
                </ul>
                
                <div class="sidebar-divider"></div>
                
                <ul>
                    <li class="nav-item">
                        <a href="#settings">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#help">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Aide</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="#logout" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="breadcrumb">
                        <span>Administration</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>Gestion des Inscriptions</span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" placeholder="Rechercher...">
                    </div>
                    <div class="header-actions">
                        <button class="action-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="notification-dot"></span>
                        </button>
                        <div class="user-profile">
                            <img src="/placeholder.svg?height=40&width=40" alt="Admin User">
                            <div class="user-info">
                                <span class="user-name">Sophie Martin</span>
                                <span class="user-role">Administrateur</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <div class="container">
                <h1>Gestion des Inscriptions</h1>
                
                <div id="success-message" class="success-message">
                    Opération réussie !
                </div>
                
                <div id="error-message" class="error-message">
                    Une erreur est survenue. Veuillez réessayer.
                </div>
                
                <!-- Formulaire d'ajout d'inscription -->
                <div class="form-container">
                    <h2 class="form-title">Ajouter une inscription</h2>
                    <form id="inscription-form" action="formulaire-inscription.php" method="POST" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="nom">Nom complet *</label>
                            <input type="text" id="nom" name="nom" placeholder="Entrez le nom complet" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="Entrez l'adresse email" required>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Entrez le numéro de téléphone" required>
                        </div>
                        <div class="form-group">
                            <label for="entreprise">Entreprise / Organisation</label>
                            <input type="text" id="entreprise" name="entreprise" placeholder="Entrez le nom de l'entreprise">
                        </div>
                        <div class="form-group">
                            <label for="fonction">Fonction</label>
                            <input type="text" id="fonction" name="fonction" placeholder="Entrez la fonction">
                        </div>
                        <div class="form-group">
                            <label for="id_evenement">Événement *</label>
                            <select id="id_evenement" name="id_evenement" required>
                                <option value="">Sélectionnez un événement</option>
                                <?php foreach ($listeEvenements as $evenement): ?>
                                <option value="<?= $evenement['id_ev']; ?>"><?= htmlspecialchars($evenement['nom']); ?> - <?= htmlspecialchars($evenement['date']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
                            <button type="submit" name="ajout" class="btn btn-primary">Ajouter l'inscription</button>
                        </div>
                    </form>
                </div>
                
                <!-- Tableau des inscriptions -->
                <div class="section">
                    <h2 class="section-title">Liste des inscriptions</h2>
                    <div class="table-container">
                        <table id="inscriptions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Entreprise</th>
                                    <th>Fonction</th>
                                    <th>Événement</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inscriptions-list">
                                <?php if (empty($listeInscriptions)): ?>
                                <tr class="no-inscriptions">
                                    <td colspan="9">Aucune inscription n'a été ajoutée</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($listeInscriptions as $inscription): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inscription['id_inscription']); ?></td>
                                        <td><?= htmlspecialchars($inscription['nom']); ?></td>
                                        <td><?= htmlspecialchars($inscription['email']); ?></td>
                                        <td><?= htmlspecialchars($inscription['telephone']); ?></td>
                                        <td><?= htmlspecialchars($inscription['entreprise'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($inscription['fonction'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($inscription['nom_evenement']); ?></td>
                                        <td><?= htmlspecialchars($inscription['date_inscription']); ?></td>
                                        <td class="action-buttons">
                                            <button class="action-btn edit-btn" onclick="openEditModal(<?= $inscription['id_inscription']; ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                Modifier
                                            </button>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                                <input type="hidden" name="id_inscription" value="<?= $inscription['id_inscription']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="action-btn delete-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal d'édition -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2 class="modal-title">Modifier l'inscription</h2>
            <form id="edit-form" action="formulaire-inscription.php" method="POST">
                <input type="hidden" id="edit-id" name="id_inscription">
                <input type="hidden" name="action" value="update">
                
                <div class="form-group">
                    <label for="edit-nom">Nom complet *</label>
                    <input type="text" id="edit-nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email *</label>
                    <input type="email" id="edit-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit-telephone">Téléphone *</label>
                    <input type="tel" id="edit-telephone" name="telephone" required>
                </div>
                <div class="form-group">
                    <label for="edit-entreprise">Entreprise / Organisation</label>
                    <input type="text" id="edit-entreprise" name="entreprise">
                </div>
                <div class="form-group">
                    <label for="edit-fonction">Fonction</label>
                    <input type="text" id="edit-fonction" name="fonction">
                </div>
                <div class="form-group">
                    <label for="edit-evenement">Événement *</label>
                    <select id="edit-evenement" name="id_evenement" required>
                        <?php foreach ($listeEvenements as $evenement): ?>
                        <option value="<?= $evenement['id_ev']; ?>"><?= htmlspecialchars($evenement['nom']); ?> - <?= htmlspecialchars($evenement['date']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Fonction pour valider le formulaire
        function validateForm() {
            const nom = document.getElementById('nom').value;
            const email = document.getElementById('email').value;
            const telephone = document.getElementById('telephone').value;
            const evenement = document.getElementById('id_evenement').value;
            
            if (!nom || !email || !telephone || !evenement) {
                alert('Veuillez remplir tous les champs obligatoires.');
                return false;
            }
            
            // Validation de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse email valide.');
                return false;
            }
            
            return true;
        }
        
        // Fonctions pour le modal d'édition
        function openEditModal(id) {
            // Récupérer les données de l'inscription
            const row = document.querySelector(`tr td:first-child:contains('${id}')`).parentNode;
            const nom = row.cells[1].textContent;
            const email = row.cells[2].textContent;
            const telephone = row.cells[3].textContent;
            const entreprise = row.cells[4].textContent !== '-' ? row.cells[4].textContent : '';
            const fonction = row.cells[5].textContent !== '-' ? row.cells[5].textContent : '';
            const evenement = row.cells[6].textContent;
            
            // Remplir le formulaire d'édition
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-telephone').value = telephone;
            document.getElementById('edit-entreprise').value = entreprise;
            document.getElementById('edit-fonction').value = fonction;
            
            // Trouver l'ID de l'événement correspondant au nom
            const evenementSelect = document.getElementById('edit-evenement');
            for (let i = 0; i < evenementSelect.options.length; i++) {
                if (evenementSelect.options[i].textContent.includes(evenement)) {
                    evenementSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Afficher le modal
            document.getElementById('edit-modal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }
        
        // Fermer le modal si l'utilisateur clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Afficher le message de succès si nécessaire
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        document.getElementById('success-message').style.display = 'block';
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
        <?php endif; ?>
        
        // Polyfill pour la méthode :contains
        if (!Element.prototype.matches) {
            Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
        }
        
        if (!Element.prototype.closest) {
            Element.prototype.closest = function(s) {
                var el = this;
                do {
                    if (el.matches(s)) return el;
                    el = el.parentElement || el.parentNode;
                } while (el !== null && el.nodeType === 1);
                return null;
            };
        }
        
        // Extension pour sélectionner les éléments contenant un texte spécifique
        document.querySelectorAll = function(selector) {
            if (selector.includes(':contains')) {
                const parts = selector.split(':contains');
                const baseSelector = parts[0];
                const textToMatch = parts[1].replace(/['"()]/g, '');
                
                const elements = document.querySelectorAll(baseSelector);
                const result = [];
                
                for (let i = 0; i < elements.length; i++) {
                    if (elements[i].textContent.includes(textToMatch)) {
                        result.push(elements[i]);
                    }
                }
                
                return result;
            } else {
                return document.querySelectorAll(selector);
            }
        };
    </script>
</body>
</html>