document.addEventListener("DOMContentLoaded", () => {
  // Éléments DOM
  const statusFilter = document.getElementById("status-filter")
  const typeFilter = document.getElementById("type-filter")
  const searchInput = document.querySelector(".search-container input")
  const consultationCards = document.querySelectorAll(".proposition-card")
  const consultationsList = document.querySelector(".consultations-list")
  const contentWrapper = document.querySelector(".content-wrapper")

  // Fonction pour filtrer les consultations
  function filterConsultations() {
    const statusValue = statusFilter.value
    const typeValue = typeFilter.value
    const searchText = searchInput.value.toLowerCase().trim()

    let visibleCount = 0

    consultationCards.forEach((card) => {
      // Récupérer les valeurs pour le filtrage
      const statusElement = card.querySelector(".consultation-status")
      const typeElement = card.querySelector(".consultation-type")
      const titleElement = card.querySelector("h3")
      const descriptionElement = card.querySelector(".card-body p")

      const cardStatus = statusElement ? statusElement.textContent.trim().toLowerCase() : ""
      const cardType = typeElement ? typeElement.textContent.trim().toLowerCase() : ""
      const cardTitle = titleElement ? titleElement.textContent.trim().toLowerCase() : ""
      const cardDescription = descriptionElement ? descriptionElement.textContent.trim().toLowerCase() : ""

      // Vérifier si la carte correspond aux critères de filtre
      const matchesStatus = statusValue === "all" || cardStatus === statusValue
      const matchesType = typeValue === "all" || cardType === typeValue
      const matchesSearch = searchText === "" || cardTitle.includes(searchText) || cardDescription.includes(searchText)

      // Afficher ou masquer la carte en fonction des filtres
      if (matchesStatus && matchesType && matchesSearch) {
        card.style.display = "block"
        // Animation pour les cartes qui apparaissent
        card.style.opacity = "1"
        card.style.transform = "translateY(0)"
        visibleCount++
      } else {
        card.style.display = "none"
        card.style.opacity = "0"
        card.style.transform = "translateY(10px)"
      }
    })

    // Vérifier s'il y a des résultats
    checkNoResults(visibleCount)
  }

  // Fonction pour vérifier s'il n'y a aucun résultat
  function checkNoResults(visibleCount) {
    // Supprimer le message "Aucun résultat" s'il existe déjà
    const existingNoResults = document.querySelector(".no-results-message")
    if (existingNoResults) {
      existingNoResults.remove()
    }

    // Afficher un message si aucun résultat
    if (visibleCount === 0) {
      const noResultsMessage = document.createElement("div")
      noResultsMessage.className = "no-results-message"
      noResultsMessage.innerHTML = `
        <div class="no-results-content">
          <i class="fas fa-search"></i>
          <h3>Aucune consultation trouvée</h3>
          <p>Essayez de modifier vos critères de recherche</p>
          <button class="reset-filters-btn">Réinitialiser les filtres</button>
        </div>
      `
      contentWrapper.appendChild(noResultsMessage)

      // Ajouter un écouteur d'événement pour le bouton de réinitialisation
      const resetButton = noResultsMessage.querySelector(".reset-filters-btn")
      resetButton.addEventListener("click", resetFilters)
    }
  }

  // Fonction pour réinitialiser tous les filtres
  function resetFilters() {
    statusFilter.value = "all"
    typeFilter.value = "all"
    searchInput.value = ""
    filterConsultations()
  }

  // Ajouter des écouteurs d'événements pour les filtres
  statusFilter.addEventListener("change", filterConsultations)
  typeFilter.addEventListener("change", filterConsultations)

  // Ajouter un écouteur d'événement pour la recherche avec délai
  let searchTimeout
  searchInput.addEventListener("input", () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(filterConsultations, 300) // Délai de 300ms pour éviter trop d'appels
  })

  // Ajouter des styles CSS pour les transitions et le message "Aucun résultat"
  const style = document.createElement("style")
  style.textContent = `
    .proposition-card {
      transition: opacity 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .no-results-message {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: rgba(36, 37, 43, 0.7);
      backdrop-filter: blur(3px);
      z-index: 10;
      animation: fadeIn 0.5s ease forwards;
    }
    
    .no-results-content {
      text-align: center;
      padding: 40px;
      background-color: rgb(45, 46, 54);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      border: 1px solid rgba(227, 196, 58, 0.2);
      max-width: 400px;
    }
    
    .no-results-content i {
      font-size: 48px;
      margin-bottom: 15px;
      color: rgba(227, 196, 58, 0.7);
    }
    
    .no-results-content h3 {
      margin: 10px 0;
      color: white;
      font-size: 20px;
    }
    
    .no-results-content p {
      margin: 10px 0 20px;
      color: rgba(255, 255, 255, 0.7);
    }
    
    .reset-filters-btn {
      background: linear-gradient(135deg, #e3c43a, #d4b52f);
      color: rgb(29, 30, 35);
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 4px 10px rgba(227, 196, 58, 0.3);
    }
    
    .reset-filters-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(227, 196, 58, 0.4);
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .content-wrapper {
      position: relative;
      min-height: 400px;
    }
  `
  document.head.appendChild(style)

  // Initialiser les filtres au chargement
  filterConsultations()
})
