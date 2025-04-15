
    document.getElementById('propositionForm').addEventListener('submit', function(event) {
        let titre = document.getElementById('titre').value;
        let description = document.getElementById('description').value;

        // Vérification du titre
        if (titre.trim().length < 3 || titre.trim().length > 100) {
            alert('Le titre doit comporter entre 3 et 100 caractères.');
            event.preventDefault();  // Empêche l'envoi du formulaire
            return;
        }

        // Vérification de la description
        if (description.trim().length < 10 || description.trim().length > 500) {
            alert('La description doit comporter entre 10 et 500 caractères.');
            event.preventDefault();  // Empêche l'envoi du formulaire
            return;
        }
    });


document.addEventListener('DOMContentLoaded', () => {
  const showFormBtn = document.getElementById('showFormBtn');
  const formContainer = document.getElementById('formContainer');
  const cancelBtn = document.getElementById('cancelBtn');
  const propositionForm = document.getElementById('propositionForm');
  

  const editButtons = document.querySelectorAll('.btn-edit');
  const formContainer2 = document.getElementById('formContainer2');
  //const propositionForm2 = document.getElementById('propositionForm');
  const cancelBtn2 = document.getElementById('cancelBtn2');

  
  // Définir la date du jour comme valeur par défaut pour le champ date
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('date_soumission').value = today;

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      formContainer2.classList.add('expanding');
      formContainer2.classList.add('active');
      
      // Faire défiler jusqu'au formulaire
      const titre = button.dataset.titre;
      const description = button.dataset.description;
      const type = button.dataset.type;
      const date = button.dataset.date;

      // Remplissage des champs
      document.getElementById('titre2').value = titre;
      document.getElementById('description2').value = description;
      document.getElementById('type2').value = type;
      document.getElementById('date_soumission2').value = date;
      document.getElementById('idmodif').value = button.dataset.id;;

        setTimeout(() => {
          formContainer2.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);

    });
});


cancelBtn2.addEventListener('click', () => {
  formContainer2.classList.remove('expanding');
  formContainer2.classList.add('collapsing');
  
  setTimeout(() => {
      formContainer2.classList.remove('active');
      formContainer2.classList.remove('collapsing');
      
      // Faire défiler jusqu'au bouton d'ajout
      showFormBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }, 500);
});
  


  
  // Afficher le formulaire avec animation
  showFormBtn.addEventListener('click', () => {
      formContainer.classList.add('expanding');
      formContainer.classList.add('active');
      
      // Faire défiler jusqu'au formulaire
      setTimeout(() => {
          formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);
  });
  
  // Masquer le formulaire
  cancelBtn.addEventListener('click', () => {
      formContainer.classList.remove('expanding');
      formContainer.classList.add('collapsing');
      
      setTimeout(() => {
          formContainer.classList.remove('active');
          formContainer.classList.remove('collapsing');
          
          // Faire défiler jusqu'au bouton d'ajout
          showFormBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 500);
  });
  
  // Gérer la soumission du formulaire
  
});



document.addEventListener('DOMContentLoaded', () => {
  // Gestion des filtres
  
  const filterButtons = document.querySelectorAll('.filter-btn');
  const propositionCards = document.querySelectorAll('.proposition-card');
  
  filterButtons.forEach(btn => {
    
      btn.addEventListener('click', () => {
          // Retirer la classe active de tous les boutons
          filterButtons.forEach(b => b.classList.remove('active'));
          // Ajouter la classe active au bouton cliqué
          btn.classList.add('active');

          // Filtrer les propositions
          const filterValue = btn.getAttribute('data-filter').toLowerCase();
          
          propositionCards.forEach(card => {
              if (filterValue === 'tous') {
               
                  card.classList.remove('hidden');
              } else {
                  const cardType = card.getAttribute('data-type').toLowerCase();
                  if (cardType === filterValue) {
                      card.classList.remove('hidden');
                  } else {
                      card.classList.add('hidden');
                  }
              }
          });
      });
  });

  // Gestion de la recherche
  const searchInput = document.querySelector('.search-input');
  const searchBtn = document.querySelector('.search-btn');

  function performSearch() {
      const searchText = searchInput.value.toLowerCase();
      
      if (searchText) {
          propositionCards.forEach(card => {
              const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();
              const cardDescription = card.querySelector('.card-description').textContent.toLowerCase();
              const cardUser = card.querySelector('.card-user span').textContent.toLowerCase();
              
              if (cardTitle.includes(searchText) || 
                  cardDescription.includes(searchText) || 
                  cardUser.includes(searchText)) {
                  card.classList.remove('hidden');
              } else {
                  card.classList.add('hidden');
              }
          });
          
          // Réinitialiser les filtres
          filterButtons.forEach(b => b.classList.remove('active'));
          filterButtons[0].classList.add('active'); // Activer "Tous"
      } else {
          // Si la recherche est vide, afficher toutes les propositions
          propositionCards.forEach(card => {
              card.classList.remove('hidden');
          });
      }
  }

  searchBtn.addEventListener('click', performSearch);

  // Permettre la recherche en appuyant sur Entrée
  searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          performSearch();
      }
  });
});



document.addEventListener('DOMContentLoaded', () => {
            // Gestion des filtres
            const filterButtons = document.querySelectorAll('.filter-btn');
            const propositionCards = document.querySelectorAll('.proposition-card');
            
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Retirer la classe active de tous les boutons
                    filterButtons.forEach(b => b.classList.remove('active'));
                    // Ajouter la classe active au bouton cliqué
                    btn.classList.add('active');

                    // Filtrer les propositions
                    const filterValue = btn.getAttribute('data-filter').toLowerCase();
                    
                    propositionCards.forEach(card => {
                        if (filterValue === 'tous') {
                            card.classList.remove('hidden');
                        } else {
                            const cardType = card.getAttribute('data-type').toLowerCase();
                            if (cardType === filterValue) {
                                card.classList.remove('hidden');
                            } else {
                                card.classList.add('hidden');
                            }
                        }
                    });
                });
            });

            // Gestion de la recherche
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.querySelector('.search-btn');

            function performSearch() {
                const searchText = searchInput.value.toLowerCase();
                
                if (searchText) {
                    propositionCards.forEach(card => {
                        const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();
                        const cardDescription = card.querySelector('.card-description').textContent.toLowerCase();
                        const cardUser = card.querySelector('.card-user span').textContent.toLowerCase();
                        
                        if (cardTitle.includes(searchText) || 
                            cardDescription.includes(searchText) || 
                            cardUser.includes(searchText)) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    
                    // Réinitialiser les filtres
                    filterButtons.forEach(b => b.classList.remove('active'));
                    filterButtons[0].classList.add('active'); // Activer "Tous"
                } else {
                    // Si la recherche est vide, afficher toutes les propositions
                    propositionCards.forEach(card => {
                        card.classList.remove('hidden');
                    });
                }
            }

            searchBtn.addEventListener('click', performSearch);

            // Permettre la recherche en appuyant sur Entrée
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        });


document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
      navToggle.addEventListener('click', function() {
        navToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
      });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (navToggle && navToggle.classList.contains('active')) {
          navToggle.classList.remove('active');
          navMenu.classList.remove('active');
        }
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 70,
            behavior: 'smooth'
          });
        }
      });
    });
    
    // Active navigation based on scroll position
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    function highlightNavLink() {
      const scrollPosition = window.scrollY + 100;
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
          navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + sectionId) {
              link.classList.add('active');
            }
          });
        }
      });
    }
    
    window.addEventListener('scroll', highlightNavLink);
    highlightNavLink();
    
    // Testimonial slider
    const testimonialTrack = document.querySelector('.testimonial-track');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const navDots = document.querySelectorAll('.nav-dot');
    const prevButton = document.querySelector('.nav-prev');
    const nextButton = document.querySelector('.nav-next');
    
    if (testimonialTrack && testimonialCards.length > 0) {
      let currentIndex = 0;
      
      function updateSlider() {
        testimonialTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        navDots.forEach((dot, index) => {
          dot.classList.toggle('active', index === currentIndex);
        });
      }
      
      if (prevButton) {
        prevButton.addEventListener('click', () => {
          currentIndex = (currentIndex - 1 + testimonialCards.length) % testimonialCards.length;
          updateSlider();
        });
      }
      
      if (nextButton) {
        nextButton.addEventListener('click', () => {
          currentIndex = (currentIndex + 1) % testimonialCards.length;
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
        currentIndex = (currentIndex + 1) % testimonialCards.length;
        updateSlider();
      }, 5000);
    }
    
    // Form submission
    const contactForm = document.querySelector('.contact-form');
    
    if (contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        
        // Here you would typically send the form data to a server
        // For this template, we'll just log it and show a success message
        console.log('Form submitted:', { name, email, subject, message });
        
        // Show success message
        const formGroups = contactForm.querySelectorAll('.form-group');
        formGroups.forEach(group => {
          group.style.display = 'none';
        });
        
        const submitButton = contactForm.querySelector('button[type="submit"]');
        submitButton.style.display = 'none';
        
        const successMessage = document.createElement('div');
        successMessage.className = 'success-message';
        successMessage.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-success); margin-bottom: 1rem;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <h3>Message envoyé!</h3>
          <p>Merci de nous avoir contactés. Nous vous répondrons dans les plus brefs délais.</p>
          <button class="btn btn-primary" id="resetForm">Envoyer un autre message</button>
        `;
        
        contactForm.appendChild(successMessage);
        
        // Reset form button
        const resetButton = document.getElementById('resetForm');
        if (resetButton) {
          resetButton.addEventListener('click', function() {
            contactForm.reset();
            successMessage.remove();
            formGroups.forEach(group => {
              group.style.display = 'block';
            });
            submitButton.style.display = 'block';
          });
        }
      });
    }
    
    // Animate elements on scroll
    const animateElements = document.querySelectorAll('.service-card, .stats-card, .testimonial-card, .contact-form-container');
    
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
    
    // Code window animation
    const codeWindow = document.querySelector('.code-window');
    if (codeWindow) {
      setTimeout(() => {
        codeWindow.classList.add('animate-in');
      }, 500);
    }
  });
  
  // Add these styles to your CSS for the animations
  document.addEventListener('DOMContentLoaded', function() {
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
      
      .code-window {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s ease, transform 0.8s ease;
      }
      
      .code-window.animate-in {
        opacity: 1;
        transform: translateY(0);
      }
      
      .success-message {
        text-align: center;
        padding: 2rem 0;
      }
    `;
    document.head.appendChild(style);
  });
  