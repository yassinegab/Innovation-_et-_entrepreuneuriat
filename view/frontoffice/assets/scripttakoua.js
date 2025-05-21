document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const newConsultationBtn = document.querySelector('.new-consultation-btn');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const secondaryBtns = document.querySelectorAll('.secondary-btn');
    const overlay = document.querySelector('.overlay');
    const consultationModal = document.getElementById('consultation-modal');
    const newConsultationModal = document.getElementById('new-consultation-modal');
    const notificationBtn = document.querySelector('.notification-btn');
    const notificationPanel = document.querySelector('.notification-panel');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const statusFilter = document.getElementById('status-filter');
    const typeFilter = document.getElementById('type-filter');
    const consultationForm = document.getElementById('consultation-form');
    const consultationsList = document.querySelector('.consultations-list');

    // Open new consultation modal
    newConsultationBtn.addEventListener('click', function () {
        openModal(newConsultationModal);
    });

    // Delegated: Open consultation details modal
    consultationsList.addEventListener('click', function (e) {
        if (e.target.classList.contains('view-details-btn')) {
            openModal(consultationModal);
        }
    });

    // Close modals
    closeModalBtns.forEach(btn => btn.addEventListener('click', closeAllModals));
    secondaryBtns.forEach(btn => btn.addEventListener('click', closeAllModals));
    overlay.addEventListener('click', closeAllModals);

    // Toggle notification panel
    notificationBtn.addEventListener('click', function () {
        notificationPanel.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    // Mark all notifications as read
    markAllReadBtn.addEventListener('click', function () {
        document.querySelectorAll('.notification-item.unread').forEach(n => n.classList.remove('unread'));
        document.querySelector('.notification-badge').style.display = 'none';
    });

    // Filter consultations
    statusFilter.addEventListener('change', filterConsultations);
    typeFilter.addEventListener('change', filterConsultations);

    // Submit new consultation form
    consultationForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const subject = document.getElementById('consultation-subject').value.trim();
        const type = document.getElementById('consultation-type').value.trim();
        const description = document.getElementById('consultation-description').value.trim();

        if (!subject || !type || !description) {
            showNotification('Veuillez remplir tous les champs.');
            return;
        }

        addNewConsultation(subject, type, description);
        consultationForm.reset();
        closeAllModals();
        showNotification('Consultation créée avec succès!');
    });

    // Helper Functions
    function openModal(modal) {
        modal.classList.add('active');
        overlay.classList.add('active');
    }

    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'));
        notificationPanel.classList.remove('active');
        overlay.classList.remove('active');
    }

    function filterConsultations() {
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        document.querySelectorAll('.consultation-card').forEach(card => {
            const cardStatus = card.querySelector('.consultation-status').classList[1];
            const cardType = card.querySelector('.consultation-type').textContent.toLowerCase();

            const statusMatch = statusValue === 'all' || cardStatus === statusValue;
            const typeMatch = typeValue === 'all' || cardType === typeValue;

            card.style.display = statusMatch && typeMatch ? 'block' : 'none';
        });
    }

    function addNewConsultation(subject, type, description) {
        const newCard = document.createElement('div');
        newCard.className = 'consultation-card';
        newCard.dataset.id = Date.now();

        const typeDisplay = type.charAt(0).toUpperCase() + type.slice(1);
        const currentDate = new Date();
        const formattedDate = `${currentDate.getDate()} ${getMonthName(currentDate.getMonth())} ${currentDate.getFullYear()}`;

        newCard.innerHTML = `
            <div class="card-header">
                <div class="consultation-info">
                    <h3>${subject}</h3>
                    <span class="consultation-type">${typeDisplay}</span>
                    <span class="consultation-status pending">En attente</span>
                </div>
                <div class="consultation-date">
                    <i class="far fa-calendar-alt"></i> ${formattedDate}
                </div>
            </div>
            <div class="card-body">
                <p>${description}</p>
            </div>
            <div class="card-footer">
                <div class="consultant-info">
                    <img src="https://via.placeholder.com/24" alt="Consultant" class="consultant-avatar">
                    <span>Non assigné</span>
                </div>
                <button class="view-details-btn">Voir détails</button>
            </div>
        `;

        consultationsList.insertBefore(newCard, consultationsList.firstChild);
    }

    function getMonthName(monthIndex) {
        const months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        return months[monthIndex];
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'toast-notification';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => notification.classList.add('show'), 100);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Toast notification styles
    const style = document.createElement('style');
    style.textContent = `
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--accent-color);
            color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
            z-index: 1000;
            font-weight: 600;
        }
        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
    `;
    document.head.appendChild(style);
});
