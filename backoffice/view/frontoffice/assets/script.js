document.addEventListener("DOMContentLoaded", function () {
  // Profile menu dropdown
  const profileMenu = document.querySelector(".profile-menu");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  if (profileMenu && dropdownMenu) {
    profileMenu.addEventListener("click", function (e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function () {
      if (dropdownMenu.classList.contains("show")) {
        dropdownMenu.classList.remove("show");
      }
    });
  }

  const commentForm = document.querySelector(".comment-form");
  const commentTextarea = commentForm?.querySelector("textarea");
  const commentButton = commentForm?.querySelector("button");

  if (commentTextarea && commentButton) {
    commentTextarea.addEventListener("focus", function () {
      this.style.height = "120px";
      commentButton.style.display = "block";
    });

    commentButton.addEventListener("click", async function () {
      const commentText = commentTextarea.value.trim();

      if (!commentText) {
        alert("Veuillez écrire un commentaire avant de publier.");
        return;
      }

      try {
        // Remplacer la partie fetch dans script.js
        const response = await fetch("index.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `article_id=${selectedArticleId}&content=${encodeURIComponent(
            commentText
          )}`,
        });

        const result = await response.json();

        if (response.ok && result.success) {
          alert("Votre commentaire a été publié !");
          commentTextarea.value = "";
          commentButton.style.display = "none";
          commentTextarea.style.height = "40px"; // optionnel, reset la hauteur du textarea
        } else if (result.error) {
          alert("Erreur : " + result.error);
        }
      } catch (error) {
        console.error("Erreur lors de l'envoi du commentaire:", error);
        alert("Erreur réseau. Merci de réessayer.");
      }
    });
  }

  // Load more comments
  const loadMoreButton = document.querySelector(".load-more");

  if (loadMoreButton) {
    loadMoreButton.addEventListener("click", function () {
      // In a real application, this would load more comments from a server
      alert("Chargement de plus de commentaires...");
    });
  }

  // Newsletter subscription
  const newsletterForm = document.querySelector(".newsletter-form");
  const emailInput = newsletterForm?.querySelector("input");
  const subscribeButton = newsletterForm?.querySelector("button");

  if (emailInput && subscribeButton) {
    subscribeButton.addEventListener("click", function () {
      const email = emailInput.value.trim();
      if (email && isValidEmail(email)) {
        // In a real application, this would send the email to a server
        alert("Merci de vous être inscrit à notre newsletter !");
        emailInput.value = "";
      } else {
        alert("Veuillez entrer une adresse email valide.");
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
  const mobileMenuButton = document.createElement("button");
  mobileMenuButton.className = "mobile-menu-button";
  mobileMenuButton.innerHTML = '<i class="fa-solid fa-bars"></i>';

  const logo = document.querySelector(".logo");
  if (logo) {
    logo.parentNode.insertBefore(mobileMenuButton, logo.nextSibling);
  }

  // Add smooth scrolling for all links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const targetId = this.getAttribute("href");
      if (targetId === "#") return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({
          behavior: "smooth",
        });
      }
    });
  });
});
// Ajoutez cette section après le code existant
document.querySelectorAll(".comments-list").forEach((commentsList) => {
  commentsList.addEventListener("click", async (e) => {
    const commentElement = e.target.closest(".comment");
    const commentId = commentElement?.dataset.id;

    // Suppression
    if (e.target.closest(".btn-delete")) {
      if (confirm("Voulez-vous vraiment supprimer ce commentaire ?")) {
        try {
          const response = await fetch(
            `../../../controller/commentairecontroller.php?action=delete&id=${commentId}`,
            {
              method: "DELETE",
            }
          );

          if (response.ok) {
            commentElement.remove();
          } else {
            alert("Erreur lors de la suppression");
          }
        } catch (error) {
          console.error("Erreur:", error);
        }
      }
    }

    // Modification
    if (e.target.closest(".btn-modify")) {
      const contentElement = commentElement.querySelector("p");
      const originalContent = contentElement.textContent;

      // Créer un textarea
      const textarea = document.createElement("textarea");
      textarea.value = originalContent;
      textarea.classList.add("edit-textarea");

      // Remplacer le contenu
      contentElement.replaceWith(textarea);
      textarea.focus();

      // Créer un bouton de sauvegarde
      const saveButton = document.createElement("button");
      saveButton.textContent = "Sauvegarder";
      saveButton.classList.add("btn-primary", "save-edit");

      textarea.after(saveButton);

      // Gérer la sauvegarde
      saveButton.addEventListener("click", async () => {
        try {
          const response = await fetch(
            `../../../controller/commentairecontroller.php?action=update`,
            {
              method: "PUT",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                id: commentId,
                content: textarea.value,
              }),
            }
          );

          if (response.ok) {
            contentElement.textContent = textarea.value;
            textarea.replaceWith(contentElement);
            saveButton.remove();
          }
        } catch (error) {
          console.error("Erreur:", error);
        }
      });
    }

    // Signalement
    if (e.target.closest(".btn-report")) {
      try {
        const response = await fetch(
          `../../../controller/commentairecontroller.php?action=report&id=${commentId}`,
          {
            method: "POST",
          }
        );

        if (response.ok) {
          alert("Commentaire signalé avec succès");
          commentElement.classList.add("reported");
        }
      } catch (error) {
        console.error("Erreur:", error);
      }
    }
    document.querySelectorAll(".btn-reply").forEach((button) => {
      button.addEventListener("click", function () {
        const commentId = this.dataset.id;
        const commentElement = this.closest(".comment");

        // Check if reply form already exists
        let replyForm = commentElement.querySelector(".reply-form");

        if (replyForm) {
          // Toggle the form if it already exists
          replyForm.classList.toggle("active");
          return;
        }

        // Create reply form
        replyForm = document.createElement("div");
        replyForm.className = "reply-form active";
        replyForm.innerHTML = `
          <textarea placeholder="Écrivez votre réponse..." class="reply-textarea"></textarea>
          <div class="reply-actions">
            <button class="btn-cancel">Annuler</button>
            <button class="btn-submit-reply">Répondre</button>
          </div>
        `;

        // Add the form after the comment content
        commentElement.appendChild(replyForm);

        // Focus the textarea
        replyForm.querySelector("textarea").focus();

        // Handle cancel button
        replyForm
          .querySelector(".btn-cancel")
          .addEventListener("click", function () {
            replyForm.remove();
          });

        // Handle submit button
        replyForm
          .querySelector(".btn-submit-reply")
          .addEventListener("click", async function () {
            const replyText = replyForm.querySelector("textarea").value.trim();

            if (!replyText) {
              alert("Veuillez écrire une réponse avant de publier.");
              return;
            }

            try {
              const response = await fetch("index.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `article_id=${selectedArticleId}&content=${encodeURIComponent(
                  replyText
                )}&parent_id=${commentId}`,
              });

              const result = await response.json();

              if (response.ok && result.success) {
                // Create and insert the new reply
                const replyElement = document.createElement("div");
                replyElement.className = "comment reply-indent";
                replyElement.dataset.id = result.comment_id;
                replyElement.innerHTML = `
                <div class="comment-header">
                  <span class="name">${result.author}</span>
                  <div class="comment-actions">
                    <button class="btn-like" data-id="${result.comment_id}">
                      <i class="far fa-heart"></i>
                      <span class="like-count">0</span>
                    </button>
                    <button class="btn-modify" title="Modifier" data-id="${result.comment_id}">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-delete" title="Supprimer" data-id="${result.comment_id}">
                      <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn-reply" title="Répondre" data-id="${result.comment_id}">
                      <i class="fas fa-reply"></i>
                    </button>
                  </div>
                </div>
                <p>${replyText}</p>
              `;

                // Insert after the parent comment
                const parentComment = document.querySelector(
                  `.comment[data-id="${commentId}"]`
                );
                if (
                  parentComment.nextElementSibling &&
                  parentComment.nextElementSibling.classList.contains(
                    "reply-container"
                  )
                ) {
                  parentComment.nextElementSibling.appendChild(replyElement);
                } else {
                  const replyContainer = document.createElement("div");
                  replyContainer.className = "reply-container";
                  replyContainer.appendChild(replyElement);
                  parentComment.after(replyContainer);
                }

                // Remove the reply form
                replyForm.remove();
              } else if (result.error) {
                alert("Erreur : " + result.error);
              }
            } catch (error) {
              console.error("Erreur lors de l'envoi de la réponse:", error);
              alert("Erreur réseau. Merci de réessayer.");
            }
          });
      });
    });
  });
});
// Gestion des emojis pour le formulaire principal
document.querySelectorAll(".emoji-button").forEach((button) => {
  const pickerContainer = button
    .closest(".form-input")
    .querySelector(".emoji-picker-container");

  const textarea = button
    .closest(".textarea-container")
    .querySelector("textarea");

  // Show/hide emoji picker
  button.addEventListener("click", (e) => {
    e.stopPropagation(); // ✅ Prevent click from bubbling
    pickerContainer.style.display =
      pickerContainer.style.display === "none" ? "block" : "none";
  });

  // Add emoji to textarea
  const picker = pickerContainer.querySelector("emoji-picker");
  picker.addEventListener("emoji-click", (event) => {
    textarea.value += event.detail.unicode;
    pickerContainer.style.display = "none";
  });
});
