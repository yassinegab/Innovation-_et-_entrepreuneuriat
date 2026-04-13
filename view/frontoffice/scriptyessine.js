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
  const commentButton = commentForm?.querySelector(".publish-comment");
  const emojiToggle = document.getElementById("emojiToggle");
  const emojiPicker = document.getElementById("emojiPicker");
  const GIPHY_API_KEY = "rFiZ4ZattdkwRCct9gs4dL4nBlMugUyW";
  const giphyToggle = document.getElementById("giphyToggle");
  const giphyPicker = document.getElementById("giphyPicker");

  if (commentTextarea && commentButton) {
    // Expand textarea on focus
    commentTextarea.addEventListener("focus", function () {
      this.style.height = "120px";
      commentButton.style.display = "block";
    });

    // Handle publish button click
    commentButton.addEventListener("click", async function () {
      const commentText = commentTextarea.value.trim();

      if (!commentText) {
        alert("Veuillez écrire un commentaire avant de publier.");
        return;
      }

      try {
        const response = await fetch("indexyessine.php", {
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
         // alert("Votre commentaire a été publié !");
          commentTextarea.value = "";
          commentButton.style.display = "none";
          commentTextarea.style.height = "40px";
          location.reload();
        } else if (result.error) {
          alert("Erreur : " + result.error);
        }
      } catch (error) {
        console.error("Erreur lors de l'envoi du commentaire:", error);
        alert("Erreur réseau. Merci de réessayer.");
      }
    });

    // Emoji picker toggle
    emojiToggle?.addEventListener("click", function (e) {
      e.preventDefault();
      emojiPicker.style.display =
        emojiPicker.style.display === "none" ? "block" : "none";
      giphyPicker.style.display = "none"; // Hide GIPHY when showing emoji
    });

    // GIPHY picker toggle
    giphyToggle?.addEventListener("click", async (e) => {
      e.preventDefault();
      emojiPicker.style.display = "none";
      const showPicker = giphyPicker.style.display === "none";
      giphyPicker.style.display = showPicker ? "block" : "none";

      if (showPicker) {
        await loadGiphyGIFs();
      }
    });

    // Emoji insertion
    emojiPicker?.addEventListener("emoji-click", function (event) {
      const emoji = event.detail.unicode;
      insertAtCursor(commentTextarea, emoji);
    });
  }

  // GIPHY Functions
  async function loadGiphyGIFs(search = "") {
    try {
      const endpoint = search ? "search" : "trending";
      const url = `https://api.giphy.com/v1/gifs/${endpoint}?api_key=${GIPHY_API_KEY}&q=${encodeURIComponent(
        search
      )}&limit=12`;

      const response = await fetch(url);
      const { data } = await response.json();

      giphyPicker.innerHTML = `
      <input type="text" class="giphy-search" placeholder="Search GIFs..." />
      ${data
        .map(
          (gif) => `
        <img class="giphy-gif" 
          src="${gif.images.fixed_height_small.url}"
          data-original="${gif.images.original.url}"
          alt="${gif.title}"
        />
      `
        )
        .join("")}
    `;

      // Search handling
      giphyPicker
        .querySelector(".giphy-search")
        .addEventListener("input", (e) => {
          loadGiphyGIFs(e.target.value);
        });

      // GIF selection
      giphyPicker.querySelectorAll(".giphy-gif").forEach((gif) => {
        gif.addEventListener("click", () => {
          const url = gif.dataset.original;
          insertAtCursor(commentTextarea, `[GIF:${url}]`);
          giphyPicker.style.display = "none";
        });
      });
    } catch (error) {
      console.error("GIPHY Error:", error);
    }
  }

  // Cursor insertion helper
  function insertAtCursor(textarea, content) {
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    textarea.value =
      textarea.value.slice(0, start) + content + textarea.value.slice(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + content.length;
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
  /*const mobileMenuButton = document.createElement("button");
  mobileMenuButton.className = "mobile-menu-button";
  mobileMenuButton.innerHTML = '<i class="fa-solid fa-bars"></i>';

  const logo = document.querySelector(".logo");
  if (logo) {
    logo.parentNode.insertBefore(mobileMenuButton, logo.nextSibling);
  }*/

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
            `../../controller/commentairecontroller.php?action=delete&id=${commentId}`,
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
            `../../controller/commentairecontroller.php?action=update`,
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
          `../../controller/commentairecontroller.php?action=report&id=${commentId}`,
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
    // Add this code after DOMContentLoaded
    document.querySelectorAll(".btn-like").forEach((button) => {
      button.addEventListener("click", async function () {
        const commentId = this.dataset.id;
        const likeCountElement = this.querySelector(".like-count");

        try {
          // Increment likes
          const likeResponse = await fetch(
            `../../controller/commentairecontroller.php?action=like&id=${commentId}`,
            { method: "POST" }
          );

          const likeResult = await likeResponse.json();

          if (likeResult.success) {
            // Get updated likes count
            const countResponse = await fetch(
              `../../controller/commentairecontroller.php?action=getLikes&id=${commentId}`
            );
            const countResult = await countResponse.json();

            likeCountElement.textContent = countResult.likes;

            // Visual feedback
            this.classList.add("liked");
            setTimeout(() => this.classList.remove("liked"), 1000);
          }
        } catch (error) {
          console.error("Like error:", error);
          alert("Error updating like");
        }
      });
    });
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
              const response = await fetch("indexyessine.php", {
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
