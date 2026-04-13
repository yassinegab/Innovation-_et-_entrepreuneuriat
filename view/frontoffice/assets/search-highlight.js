document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(".search-container input")

  // Fonction pour mettre en surbrillance les termes de recherche
  function highlightSearchTerms() {
    const searchText = searchInput.value.trim().toLowerCase()
    if (!searchText) {
      // Si la recherche est vide, supprimer toutes les surbrillances
      removeAllHighlights()
      return
    }

    // Sélectionner tous les titres et descriptions des consultations visibles
    const visibleCards = document.querySelectorAll('.consultation-card[style*="display: block"]')

    visibleCards.forEach((card) => {
      const titleElement = card.querySelector("h3")
      const descriptionElement = card.querySelector(".card-body p")

      // Supprimer les surbrillances existantes
      if (titleElement) removeHighlights(titleElement)
      if (descriptionElement) removeHighlights(descriptionElement)

      // Ajouter de nouvelles surbrillances
      if (titleElement) highlightText(titleElement, searchText)
      if (descriptionElement) highlightText(descriptionElement, searchText)
    })
  }

  // Fonction pour supprimer toutes les surbrillances
  function removeAllHighlights() {
    document.querySelectorAll(".search-highlight").forEach((el) => {
      const parent = el.parentNode
      parent.replaceChild(document.createTextNode(el.textContent), el)
      parent.normalize()
    })
  }

  // Fonction pour supprimer les surbrillances d'un élément spécifique
  function removeHighlights(element) {
    const highlights = element.querySelectorAll(".search-highlight")
    highlights.forEach((el) => {
      const parent = el.parentNode
      parent.replaceChild(document.createTextNode(el.textContent), el)
      parent.normalize()
    })
  }

  // Fonction pour mettre en surbrillance le texte
  function highlightText(element, searchText) {
    const innerHTML = element.innerHTML
    const index = innerHTML.toLowerCase().indexOf(searchText)

    if (index >= 0) {
      const originalText = innerHTML.substring(index, index + searchText.length)
      const newText = `<span class="search-highlight">${originalText}</span>`
      element.innerHTML = innerHTML.substring(0, index) + newText + innerHTML.substring(index + searchText.length)
    }
  }

  // Ajouter un style pour la surbrillance
  const style = document.createElement("style")
  style.textContent = `
    .search-highlight {
      background-color: rgba(227, 196, 58, 0.3);
      border-radius: 3px;
      padding: 0 2px;
      font-weight: bold;
    }
  `
  document.head.appendChild(style)

  // Ajouter un écouteur d'événement pour la recherche
  let highlightTimeout
  searchInput.addEventListener("input", () => {
    clearTimeout(highlightTimeout)
    highlightTimeout = setTimeout(highlightSearchTerms, 500) // Délai pour éviter trop d'appels
  })
})
