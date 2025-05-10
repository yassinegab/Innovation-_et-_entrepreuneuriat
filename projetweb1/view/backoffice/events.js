document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    const eventsGrid = document.querySelector('.upcoming-events');
    const calendarView = document.getElementById('calendar-view');
    
    if (viewButtons.length > 0) {
      viewButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all buttons
          viewButtons.forEach(btn => btn.classList.remove('active'));
          
          // Add active class to clicked button
          this.classList.add('active');
          
          // Toggle view based on data-view attribute
          const viewType = this.getAttribute('data-view');
          
          if (viewType === 'grid') {
            eventsGrid.style.display = 'block';
            calendarView.style.display = 'none';
          } else if (viewType === 'calendar') {
            eventsGrid.style.display = 'none';
            calendarView.style.display = 'block';
          } else if (viewType === 'list') {
            // For list view, we could add another section or modify the grid
            // For now, just show the grid
            eventsGrid.style.display = 'block';
            calendarView.style.display = 'none';
          }
        });
      });
    }
    
    // Featured events slider
    const featuredTrack = document.querySelector('.featured-events-track');
    const featuredCards = document.querySelectorAll('.featured-event-card');
    const navDots = document.querySelectorAll('.nav-dot');
    const prevButton = document.querySelector('.nav-prev');
    const nextButton = document.querySelector('.nav-next');
    
    if (featuredTrack && featuredCards.length > 0) {
      let currentIndex = 0;
      
      function updateSlider() {
        featuredTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        navDots.forEach((dot, index) => {
          dot.classList.toggle('active', index === currentIndex);
        });
      }
      
      if (prevButton) {
        prevButton.addEventListener('click', () => {
          currentIndex = (currentIndex - 1 + featuredCards.length) % featuredCards.length;
          updateSlider();
        });
      }
      
      if (nextButton) {
        nextButton.addEventListener('click', () => {
          currentIndex = (currentIndex + 1) % featuredCards.length;
          updateSlider();
        });
      }
      
      navDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          currentIndex = index;
          updateSlider();
        });
      });
      
      // Auto slide every 5 seconds
      setInterval(() => {
        currentIndex = (currentIndex + 1) % featuredCards.length;
        updateSlider();
      }, 5000);
    }
    
    // Filter reset button
    const filterReset = document.querySelector('.filter-reset');
    const filterSelects = document.querySelectorAll('.filter-select');
    
    if (filterReset && filterSelects.length > 0) {
      filterReset.addEventListener('click', () => {
        filterSelects.forEach(select => {
          select.selectedIndex = 0;
        });
      });
    }
    
    // Load more events button
    const loadMoreBtn = document.querySelector('.load-more');
    
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function() {
        // This would typically fetch more events from a server
        // For demo purposes, we'll just show a message
        this.innerHTML = 'Chargement...';
        
        setTimeout(() => {
          this.innerHTML = 'Tous les événements sont chargés';
          this.disabled = true;
          this.classList.add('disabled');
        }, 1500);
      });
    }
    
    // Calendar navigation
    const calendarTitle = document.querySelector('.calendar-title');
    const calendarPrev = document.querySelector('.calendar-nav.prev');
    const calendarNext = document.querySelector('.calendar-nav.next');
    
    if (calendarTitle && calendarPrev && calendarNext) {
      const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
      let currentDate = new Date();
      
      function updateCalendarTitle() {
        calendarTitle.textContent = `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
      }
      
      calendarPrev.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendarTitle();
        // In a real app, we would regenerate the calendar days here
      });
      
      calendarNext.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendarTitle();
        // In a real app, we would regenerate the calendar days here
      });
    }
    
    // Calendar events tooltip
    const calendarEvents = document.querySelectorAll('.calendar-event');
    
    if (calendarEvents.length > 0) {
      calendarEvents.forEach(event => {
        event.addEventListener('click', function() {
          alert(`Événement: ${this.textContent}\nCliquez pour plus de détails.`);
        });
      });
    }
    
    // Search functionality
    const searchForm = document.querySelector('.events-search');
    const searchInput = document.querySelector('.search-input');
    
    if (searchForm && searchInput) {
      searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const searchTerm = searchInput.value.trim();
        
        if (searchTerm) {
          alert(`Recherche pour: "${searchTerm}"\nCette fonctionnalité serait implémentée avec une base de données réelle.`);
        }
      });
    }
    
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
      newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = this.querySelector('input[type="email"]');
        const email = emailInput.value.trim();
        
        if (email) {
          // This would typically send the email to a server
          alert(`Merci de vous être abonné avec l'email: ${email}`);
          emailInput.value = '';
        }
      });
    }
    
    // Animate elements on scroll
    const animateElements = document.querySelectorAll('.event-card, .featured-event-card, .past-event-card');
    
    function isElementInViewport(el) {
      const rect = el.getBoundingClientRect();
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    }
    
    function animateOnScroll() {
      animateElements.forEach(element => {
        if (isElementInViewport(element)) {
          element.classList.add('animate-in');
        }
      });
    }
    
    // Add initial classes
    animateElements.forEach(element => {
      element.classList.add('animate-item');
    });
    
    // Check on scroll
    window.addEventListener('scroll', animateOnScroll);
    
    // Check on initial load
    animateOnScroll();
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
      .animate-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
      }
      
      .animate-in {
        opacity: 1;
        transform: translateY(0);
      }
    `;
    document.head.appendChild(style);
  });