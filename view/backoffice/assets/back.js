const items = [
    {
        id: 1,
        titre: "Demande de congé",
        description: "Demande de congé pour la période du 15 au 30 juin pour raisons personnelles.",
        type: "Congé",
        date_soumission: "10 mai 2023",
        statut: "En attente"
    },
    {
        id: 2,
        titre: "Rapport mensuel",
        description: "Rapport d'activité pour le mois d'avril concernant le projet Alpha.",
        type: "Rapport",
        date_soumission: "5 mai 2023",
        statut: "Approuvé"
    },
    {
        id: 3,
        titre: "Demande de matériel",
        description: "Demande d'acquisition d'un nouvel ordinateur portable pour le département marketing.",
        type: "Matériel",
        date_soumission: "8 mai 2023",
        statut: "Rejeté"
    },
    {
        id: 4,
        titre: "Proposition commerciale",
        description: "Proposition commerciale pour le client XYZ concernant le nouveau service de consultation.",
        type: "Commercial",
        date_soumission: "12 mai 2023",
        statut: "En attente"
    },
    {
        id: 5,
        titre: "Formation technique",
        description: "Demande de participation à une formation technique sur les nouvelles technologies cloud.",
        type: "Formation",
        date_soumission: "3 mai 2023",
        statut: "Approuvé"
    }
];

// Update item status
function updateStatus(id, newStatus) {
    // Find the item in our data
    const item = items.find(item => item.id == id);
    if (item) {
        item.statut = newStatus;
        
        // Update the HTML directly
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            // Update status cell
            const statusCell = row.querySelector('td:nth-child(5)');
            let statusClass = '';
            switch(newStatus) {
                case 'En attente':
                    statusClass = 'status-pending';
                    break;
                case 'Approuvé':
                    statusClass = 'status-approved';
                    break;
                case 'Rejeté':
                    statusClass = 'status-rejected';
                    break;
            }
            statusCell.innerHTML = `<span class="status ${statusClass}">${newStatus}</span>`;
            
            // Update actions cell - remove approve/reject buttons if not pending
            const actionsCell = row.querySelector('td:nth-child(6)');
            if (newStatus !== 'En attente') {
                actionsCell.innerHTML = `
                    <button class="btn btn-sm btn-view btn-icon-text" data-id="${id}">
                        <svg class="icon icon-sm" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"></path>
                        </svg>
                        Voir
                    </button>
                `;
            }
            
            // Re-add event listeners
            addButtonEventListeners();
        }
    }
}
function toggleSubmenu(event) {
    
    event.preventDefault(); // Empêche le lien de naviguer
    const submenu = event.currentTarget.nextElementSibling; // Récupère le sous-menu
    submenu.classList.toggle('active'); // Bascule la classe active
}
// Show item details in modal
function showItemDetails(id) {
    const item = items.find(item => item.id == id);
    if (!item) return;

    const modal = document.getElementById('detailModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const modalApprove = document.getElementById('modalApprove');
    const modalReject = document.getElementById('modalReject');

    modalTitle.textContent = item.titre;
    
    modalBody.innerHTML = `
        <div class="detail-row">
            <span class="detail-label">Description</span>
            <p class="detail-value">${item.description}</p>
        </div>
        <div class="detail-row">
            <span class="detail-label">Type</span>
            <p class="detail-value">${item.type}</p>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date de Soumission</span>
            <p class="detail-value">${item.date_soumission}</p>
        </div>
        <div class="detail-row">
            <span class="detail-label">Statut</span>
            <p class="detail-value">
                <span class="status ${item.statut === 'En attente' ? 'status-pending' : item.statut === 'Approuvé' ? 'status-approved' : 'status-rejected'}">
                    ${item.statut}
                </span>
            </p>
        </div>
    `;

    // Show/hide approve/reject buttons based on status
    if (item.statut === 'En attente') {
        modalApprove.style.display = 'flex';
        modalReject.style.display = 'flex';
        
        // Add event listeners to modal buttons
        modalApprove.onclick = function() {
            updateStatus(id, 'Approuvé');
            modal.style.display = 'none';
        };
        
        modalReject.onclick = function() {
            updateStatus(id, 'Rejeté');
            modal.style.display = 'none';
        };
    } else {
        modalApprove.style.display = 'none';
        modalReject.style.display = 'none';
    }

    modal.style.display = 'flex';
}





// Add event listeners to buttons
function addButtonEventListeners() {
    // View buttons
    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showItemDetails(id);
        });
    });

    // Approve buttons
    document.querySelectorAll('.btn-approve').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            updateStatus(id, 'Approuvé');
        });
    });

    // Reject buttons
    document.querySelectorAll('.btn-reject').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            updateStatus(id, 'Rejeté');
        });
    });
}

// Close modal
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('detailModal').style.display = 'none';
});

document.getElementById('modalClose').addEventListener('click', function() {
    document.getElementById('detailModal').style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
});

// Mobile menu toggle
document.getElementById('mobileMenuToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('active');
});

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    addButtonEventListeners();
});

