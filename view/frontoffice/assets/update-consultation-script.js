document.addEventListener("DOMContentLoaded", () => {
    // Function to save evaluation and update consultation status
    function saveEvaluationAndComplete(consultationId) {
      console.log("Saving evaluation and completing consultation ID:", consultationId)
  
      // Get the rating from active stars
      const activeStars = document.querySelectorAll(".star-btn.active")
      const rating = activeStars.length
  
      // Get the comment from textarea
      const commentTextarea = document.querySelector(".evaluation-comment textarea")
      const comment = commentTextarea ? commentTextarea.value.trim() : ""
  
      // Create form data
      const formData = new FormData()
      formData.append("consultation_id", consultationId)
      formData.append("rating", rating)
      formData.append("comment", comment)
  
      // Send AJAX request to save evaluation
      fetch("save-evaluation.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) throw new Error("Erreur réseau: " + response.status)
          return response.json()
        })
        .then((data) => {
          console.log("Evaluation save response:", data)
  
          if (data.success) {
            // Fermer les modals
            const confirmationModal = document.getElementById("confirmation-modal")
            if (confirmationModal && confirmationModal.classList.contains("active")) {
              confirmationModal.classList.remove("active")
            }
  
            const detailsModal = document.getElementById("details-modal")
            if (detailsModal && detailsModal.classList.contains("modal-active")) {
              detailsModal.classList.remove("modal-active")
              detailsModal.style.display = "none"
            }
  
            // Mettre à jour le statut dans la liste si la carte existe
            const card = document.querySelector(`.consultation-card[data-id="${consultationId}"]`)
            if (card) {
              const statusBadge = card.querySelector(".consultation-status")
              if (statusBadge) {
                // Supprimer toutes les classes de statut
                statusBadge.classList.remove("pending", "in-progress", "cancelled")
                // Ajouter la classe completed
                statusBadge.classList.add("completed")
                // Mettre à jour le texte
                statusBadge.textContent = "completed"
              }
            }
  
            // Show success notification
            showNotification("Consultation évaluée et complétée avec succès!")
  
            // Reload the page to reflect changes
            setTimeout(() => {
              window.location.href = "consultationList.php"
            }, 1500)
          } else {
            showNotification("Erreur: " + (data.message || "Impossible d'enregistrer l'évaluation."), "error")
          }
        })
        .catch((error) => {
          console.error("Error saving evaluation:", error)
          showNotification("Erreur: " + error.message, "error")
        })
    }
  
    // Function to show notification
    function showNotification(message, type = "success") {
      // Create notification element
      const notification = document.createElement("div")
      notification.className = "success-notification"
  
      let icon = '<i class="fas fa-check-circle"></i>'
      if (type === "error") {
        notification.style.borderLeft = "4px solid #ff6b6b"
        icon = '<i class="fas fa-exclamation-circle"></i>'
      }
  
      notification.innerHTML = `
              <div class="notification-content">
                  ${icon}
                  <p>${message}</p>
              </div>
          `
  
      // Add styles for notification
      notification.style.position = "fixed"
      notification.style.bottom = "20px"
      notification.style.right = "20px"
      notification.style.backgroundColor = "#24252b"
      notification.style.color = "white"
      notification.style.padding = "15px 20px"
      notification.style.borderRadius = "10px"
      notification.style.boxShadow = "0 5px 15px rgba(0,0,0,0.3)"
      notification.style.zIndex = "9999"
      notification.style.display = "flex"
      notification.style.alignItems = "center"
      notification.style.gap = "10px"
      notification.style.transform = "translateY(100px)"
      notification.style.opacity = "0"
      notification.style.transition = "all 0.3s ease"
  
      // Add notification to document
      document.body.appendChild(notification)
  
      // Animate notification entry
      setTimeout(() => {
        notification.style.transform = "translateY(0)"
        notification.style.opacity = "1"
      }, 10)
  
      // Remove notification after 5 seconds
      setTimeout(() => {
        notification.style.transform = "translateY(100px)"
        notification.style.opacity = "0"
        setTimeout(() => {
          document.body.removeChild(notification)
        }, 300)
      }, 5000)
    }
  
    // Initialize star rating functionality
    function initStarRating() {
      const starButtons = document.querySelectorAll(".star-btn")
      if (!starButtons.length) return
  
      let currentRating = 0
  
      starButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
          currentRating = index + 1
  
          // Update star display
          starButtons.forEach((btn, idx) => {
            if (idx < currentRating) {
              btn.classList.add("active")
              btn.innerHTML = "★" // Filled star
            } else {
              btn.classList.remove("active")
              btn.innerHTML = "☆" // Empty star
            }
          })
        })
      })
    }
  
    // Initialize the script
    initStarRating()
  
    // Add event listener to the confirmation button if it exists
    const confirmBtn = document.querySelector("#confirmation-modal .confirm-btn")
    if (confirmBtn) {
      confirmBtn.addEventListener("click", () => {
        const consultationId = document.getElementById("consultation-id-to-complete").value
        if (consultationId) {
          saveEvaluationAndComplete(consultationId)
        }
      })
    }
  })
  