document.addEventListener('DOMContentLoaded', function() {
    // Profile menu dropdown
    const profileMenu = document.querySelector('.profile-menu');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (profileMenu && dropdownMenu) {
        profileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function() {
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
    
    // Comment form
    const commentForm = document.querySelector('.comment-form');
    const commentTextarea = commentForm?.querySelector('textarea');
    const commentButton = commentForm?.querySelector('button');
    
    if (commentTextarea && commentButton) {
        commentTextarea.addEventListener('focus', function() {
            this.style.height = '120px';
            commentButton.style.display = 'block';
        });
        
        commentButton.addEventListener('click', function() {
            const commentText = commentTextarea.value.trim();
            if (commentText) {
                // In a real application, this would send the comment to a server
                alert('Votre commentaire a été publié !');
                commentTextarea.value = '';
            }
        });
    }
    
    // Load more comments
    const loadMoreButton = document.querySelector('.load-more');
    
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function() {
            // In a real application, this would load more comments from a server
            alert('Chargement de plus de commentaires...');
        });
    }
    
    // Newsletter subscription
    const newsletterForm = document.querySelector('.newsletter-form');
    const emailInput = newsletterForm?.querySelector('input');
    const subscribeButton = newsletterForm?.querySelector('button');
    
    if (emailInput && subscribeButton) {
        subscribeButton.addEventListener('click', function() {
            const email = emailInput.value.trim();
            if (email && isValidEmail(email)) {
                // In a real application, this would send the email to a server
                alert('Merci de vous être inscrit à notre newsletter !');
                emailInput.value = '';
            } else {
                alert('Veuillez entrer une adresse email valide.');
            }
        });
    }
    
    // Helper function to validate email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Mobile menu toggle (would be implemented in a real application)
    // This is just a placeholder for demonstration
    const mobileMenuButton = document.createElement('button');
    mobileMenuButton.className = 'mobile-menu-button';
    mobileMenuButton.innerHTML = '<i class="fa-solid fa-bars"></i>';
    
    const logo = document.querySelector('.logo');
    if (logo) {
        logo.parentNode.insertBefore(mobileMenuButton, logo.nextSibling);
    }
    
    // Add smooth scrolling for all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});