document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
});

// Modal functions
function openCancelModal(collaboratorName) {
    document.getElementById('cancelCollaboratorName').textContent = collaboratorName;
    document.getElementById('cancelModal').classList.add('active');
}

function openMessageModal(collaboratorName) {
    document.getElementById('messageCollaboratorName').textContent = collaboratorName;
    document.getElementById('messageModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const cancelModal = document.getElementById('cancelModal');
    const messageModal = document.getElementById('messageModal');
    
    if (event.target === cancelModal) {
        closeModal('cancelModal');
    }
    
    if (event.target === messageModal) {
        closeModal('messageModal');
    }
});

// Sorting functionality (placeholder)
document.querySelectorAll('th').forEach(header => {
    header.addEventListener('click', function() {
        // Sorting logic would go here
        alert('Tri par ' + this.textContent.trim());
    });
});