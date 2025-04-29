document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (mobileMenuToggle && mainNav) {
      mobileMenuToggle.addEventListener('click', function() {
        mainNav.classList.toggle('active');
        this.classList.toggle('active');
      });
    }
    
    // Filter tabs functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    if (filterTabs.length > 0) {
      filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
          // Remove active class from all tabs
          filterTabs.forEach(t => t.classList.remove('active'));
          
          // Add active class to clicked tab
          this.classList.add('active');
          
          // Get the filter value
          const filter = this.getAttribute('data-filter');
          
          // Here you would typically filter the projects based on the selected filter
          console.log(`Filter selected: ${filter}`);
          
          // For a real implementation, you would filter the projects here
          // filterProjects(filter);
        });
      });
    }
    
    // View options functionality
    const viewOptions = document.querySelectorAll('.view-option');
    const projectsGrid = document.querySelector('.projects-grid');
    
    if (viewOptions.length > 0 && projectsGrid) {
      viewOptions.forEach(option => {
        option.addEventListener('click', function() {
          // Remove active class from all options
          viewOptions.forEach(o => o.classList.remove('active'));
          
          // Add active class to clicked option
          this.classList.add('active');
          
          // Get the view type
          const viewType = this.getAttribute('data-view');
          
          // Update the projects container class
          if (viewType === 'grid') {
            projectsGrid.classList.remove('projects-list');
            projectsGrid.classList.add('projects-grid');
          } else {
            projectsGrid.classList.remove('projects-grid');
            projectsGrid.classList.add('projects-list');
          }
          
          console.log(`View changed to: ${viewType}`);
        });
      });
    }
    
    // Carousel functionality
    const carouselTrack = document.querySelector('.carousel-track');
    const carouselItems = document.querySelectorAll('.carousel-item');
    const prevButton = document.querySelector('.carousel-arrow.prev');
    const nextButton = document.querySelector('.carousel-arrow.next');
    
    if (carouselTrack && carouselItems.length > 0) {
      let currentIndex = 0;
      const itemWidth = carouselItems[0].offsetWidth + 24; // Width + gap
      
      // Update carousel position
      const updateCarousel = () => {
        carouselTrack.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
      };
      
      // Handle next button click
      if (nextButton) {
        nextButton.addEventListener('click', () => {
          if (currentIndex < carouselItems.length - 1) {
            currentIndex++;
            updateCarousel();
          }
        });
      }
      
      // Handle prev button click
      if (prevButton) {
        prevButton.addEventListener('click', () => {
          if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
          }
        });
      }
      
      // Add smooth scrolling to carousel
      carouselTrack.style.transition = 'transform 0.3s ease';
      
      // Handle touch events for mobile swipe
      let startX, moveX;
      
      carouselTrack.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
      });
      
      carouselTrack.addEventListener('touchmove', (e) => {
        moveX = e.touches[0].clientX;
      });
      
      carouselTrack.addEventListener('touchend', () => {
        if (startX - moveX > 50 && currentIndex < carouselItems.length - 1) {
          // Swipe left
          currentIndex++;
          updateCarousel();
        } else if (moveX - startX > 50 && currentIndex > 0) {
          // Swipe right
          currentIndex--;
          updateCarousel();
        }
      });
    }
    
    // Project bookmark functionality
    const bookmarkButtons = document.querySelectorAll('.project-bookmark');
    
    if (bookmarkButtons.length > 0) {
      bookmarkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          // Toggle active class
          this.classList.toggle('active');
          
          // Get the project title
          const projectTitle = this.closest('.project-card, .featured-project-card').querySelector('.project-title, .featured-project-title').textContent;
          
          if (this.classList.contains('active')) {
            console.log(`Project bookmarked: ${projectTitle}`);
            // For a real implementation, you would save the bookmark to the user's profile
            // saveBookmark(projectId);
          } else {
            console.log(`Project bookmark removed: ${projectTitle}`);
            // For a real implementation, you would remove the bookmark from the user's profile
            // removeBookmark(projectId);
          }
        });
      });
    }
    
    // Project card click functionality
    const projectCards = document.querySelectorAll('.project-card');
    
    if (projectCards.length > 0) {
      projectCards.forEach(card => {
        card.addEventListener('click', function(e) {
          // Don't navigate if the click was on a button or link
          if (e.target.closest('button, a')) {
            return;
          }
          
          // Get the project title
          const projectTitle = this.querySelector('.project-title').textContent;
          
          console.log(`Project clicked: ${projectTitle}`);
          
          // For a real implementation, you would navigate to the project details page
          // window.location.href = `project-details.html?id=${projectId}`;
        });
      });
    }
    
    // Load more button functionality
    const loadMoreBtn = document.querySelector('.load-more-btn');
    
    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function() {
        console.log('Load more projects clicked');
        
        // For a real implementation, you would load more projects
        // loadMoreProjects().then(projects => {
        //   renderProjects(projects);
        // });
        
        // For this demo, simulate loading
        this.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-spin">
            <line x1="12" y1="2" x2="12" y2="6"></line>
            <line x1="12" y1="18" x2="12" y2="22"></line>
            <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
            <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
            <line x1="2" y1="12" x2="6" y2="12"></line>
            <line x1="18" y1="12" x2="22" y2="12"></line>
            <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
            <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
          </svg>
          Chargement...
        `;
        
        setTimeout(() => {
          this.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="1 4 1 10 7 10"></polyline>
              <polyline points="23 20 23 14 17 14"></polyline>
              <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
            </svg>
            Charger plus de projets
          `;
        }, 1500);
      });
    }
    
    // Create project modal functionality
    const createProjectBtns = document.querySelectorAll('#create-project-btn, #cta-create-project-btn');
    const createProjectModal = document.getElementById('create-project-modal');
    const modalClose = document.querySelector('.modal-close');
    const modalCancel = document.querySelector('.modal-cancel');
    const createProjectForm = document.getElementById('create-project-form');
    
    if (createProjectBtns.length > 0 && createProjectModal) {
      // Open modal
      createProjectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          createProjectModal.classList.add('active');
          document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        });
      });
      
      // Close modal functions
      const closeModal = function() {
        createProjectModal.classList.remove('active');
        document.body.style.overflow = ''; // Re-enable scrolling
      };
      
      // Close modal when clicking the close button
      if (modalClose) {
        modalClose.addEventListener('click', closeModal);
      }
      
      // Close modal when clicking the cancel button
      if (modalCancel) {
        modalCancel.addEventListener('click', closeModal);
      }
      
      // Close modal when clicking outside the modal content
      createProjectModal.addEventListener('click', function(e) {
        if (e.target === createProjectModal) {
          closeModal();
        }
      });
      
      // Handle form submission
      if (createProjectForm) {
        createProjectForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Here you would typically validate the form and submit it
          const formData = new FormData(createProjectForm);
          const projectData = {};
          
          formData.forEach((value, key) => {
            projectData[key] = value;
          });
          
          console.log('Project data to submit:', projectData);
          
          // For a real implementation, you would submit the form data to the server
          // submitProjectForm(projectData).then(() => {
          //   closeModal();
          //   // Refresh the projects list or redirect to the new project
          // });
          
          // For this demo, just close the modal
          closeModal();
        });
      }
    }
    
    // File upload preview
    const fileInput = document.getElementById('project-image');
    const fileLabel = document.querySelector('.file-upload-label');
    
    if (fileInput && fileLabel) {
      fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
          const fileName = this.files[0].name;
          fileLabel.querySelector('span').textContent = fileName;
        }
      });
    }
    
    // Dropdown filter apply button
    const applyFilterBtn = document.querySelector('.dropdown-footer .btn-primary');
    
    if (applyFilterBtn) {
      applyFilterBtn.addEventListener('click', function() {
        // Get all checked checkboxes
        const checkedSectors = Array.from(document.querySelectorAll('input[name="sector"]:checked')).map(cb => cb.value);
        const checkedStages = Array.from(document.querySelectorAll('input[name="stage"]:checked')).map(cb => cb.value);
        const checkedNeeds = Array.from(document.querySelectorAll('input[name="needs"]:checked')).map(cb => cb.value);
        
        const filters = {
          sectors: checkedSectors,
          stages: checkedStages,
          needs: checkedNeeds
        };
        
        console.log('Applied filters:', filters);
        
        // For a real implementation, you would filter the projects here
        // filterProjects(filters);
        
        // Close the dropdown
        document.querySelector('.dropdown-toggle').click();
      });
    }
    
    // Reset filters button
    const resetFilterBtn = document.querySelector('.dropdown-footer .btn-secondary');
    
    if (resetFilterBtn) {
      resetFilterBtn.addEventListener('click', function() {
        // Uncheck all checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
          cb.checked = false;
        });
        
        console.log('Filters reset');
        
        // For a real implementation, you would reset the projects here
        // filterProjects({});
      });
    }
  });