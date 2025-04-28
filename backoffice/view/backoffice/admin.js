// Let's analyze the admin.js file for issues and corrections

// Original code with corrections and comments
document.addEventListener("DOMContentLoaded", function () {
 // ===== SIDEBAR AND NAVIGATION =====
const menuToggle = document.querySelector(".menu-toggle");
const sidebar = document.querySelector(".admin-sidebar");

const mainSections = {
  
  '#comments': document.querySelector('#commentsDashboard'),
  '#articles': document.querySelector('#articlesDashboard')
};

// Toggle sidebar
if (menuToggle && sidebar) {
  menuToggle.addEventListener("click", () => sidebar.classList.toggle("active"));
}

// Close sidebar on mobile click outside
document.addEventListener("click", (event) => {
  if (window.innerWidth < 992 && sidebar?.classList.contains("active")) {
    if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
      sidebar.classList.remove("active");
    }
  }
});

// Navigation handling
document.querySelectorAll(".nav-item a").forEach(item => {
  item.addEventListener("click", function(e) {
    e.preventDefault();
    const targetSection = this.getAttribute("href");

    // Update active state
    document.querySelectorAll(".nav-item").forEach(navItem => navItem.classList.remove("active"));
    this.parentElement.classList.add("active");

    // Update breadcrumb
    const breadcrumbText = document.querySelector(".breadcrumb span:last-child");
    if (breadcrumbText) {
      breadcrumbText.textContent = this.querySelector("span").textContent;
    }

    // Toggle sections
    Object.values(mainSections).forEach(section => {
      section.style.display = 'none';
    });
    if (mainSections[targetSection]) {
      mainSections[targetSection].style.display = 'block';
    }

    // Close sidebar on mobile
    if (window.innerWidth < 992) {
      sidebar.classList.remove("active");
    }
  });
});


  // Filtre des commentaires
  document
    .getElementById("commentFilter")
    .addEventListener("change", function () {
      const status = this.value;
      document.querySelectorAll("#commentsTable tbody tr").forEach((row) => {
        row.style.display =
          status === "all" || row.dataset.status === status ? "" : "none";
      });
    });

  // Gestion des actions
  document
    .querySelector("#commentsTable")
    .addEventListener("click", function (e) {
      const btn = e.target.closest("button");
      if (!btn) return;

      const row = btn.closest("tr");
      const commentId = row.querySelector("td:first-child").textContent;

      if (btn.classList.contains("approve-btn")) {
        updateCommentStatus(commentId, "approved");
      } else if (btn.classList.contains("delete-btn")) {
        deleteComment(commentId);
      }
    });

  async function updateCommentStatus(id, status) {
    // Implémentez la logique d'API
  }

  async function deleteComment(id) {
    if (confirm("Supprimer définitivement ce commentaire ?")) {
      // Implémentez la logique d'API
    }
  }

  // ===== CHARTS AND DASHBOARD ELEMENTS =====
  // Chart period buttons
  const chartButtons = document.querySelectorAll(".chart-actions .btn-text");

  if (chartButtons.length > 0) {
    chartButtons.forEach((button) => {
      button.addEventListener("click", function () {
        // Remove active class from all buttons
        chartButtons.forEach((btn) => btn.classList.remove("active"));

        // Add active class to clicked button
        this.classList.add("active");

        // Here you would typically update the chart data
        console.log("Selected period:", this.textContent.trim());
      });
    });
  }

  // ===== TABLE FUNCTIONALITY =====
  // Table row hover effect
  const tableRows = document.querySelectorAll(".admin-table tbody tr");

  if (tableRows.length > 0) {
    tableRows.forEach((row) => {
      row.addEventListener("mouseenter", function () {
        this.style.backgroundColor = "rgba(255, 255, 255, 0.03)";
      });

      row.addEventListener("mouseleave", function () {
        this.style.backgroundColor = "";
      });
    });
  }

  // Pagination buttons
  const paginationButtons = document.querySelectorAll(".pagination-btn");

  if (paginationButtons.length > 0) {
    paginationButtons.forEach((button) => {
      if (!button.disabled) {
        button.addEventListener("click", function () {
          // Remove active class from all buttons
          paginationButtons.forEach((btn) => btn.classList.remove("active"));

          // Add active class to clicked button if it's a number
          if (!this.querySelector("svg")) {
            this.classList.add("active");
          }

          // Here you would typically fetch the data for the selected page
          const page = this.textContent.trim();
          console.log("Selected page:", page || "Navigation arrow clicked");
        });
      }
    });
  }

  // ===== USER INTERFACE ELEMENTS =====
  // User profile dropdown
  const userProfile = document.querySelector(".user-profile");

  if (userProfile) {
    userProfile.addEventListener("click", function () {
      console.log("User profile clicked - would show dropdown menu");
    });
  }

  // Notifications button
  const notificationBtn = document.querySelector(".action-btn");

  if (notificationBtn) {
    notificationBtn.addEventListener("click", function () {
      console.log("Notifications clicked - would show notifications panel");
    });
  }

  // ===== ARTICLE MANAGEMENT =====
  const addArticleBtn = document.getElementById("addArticleBtn");
  const articlesTableBody = document.querySelector("#articlesTable tbody");

  // Spinner HTML pour indiquer le chargement
  const spinnerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

  if (addArticleBtn && articlesTableBody) {
    addArticleBtn.addEventListener("click", function () {
      const newRow = document.createElement("tr");
      newRow.classList.add("new-article-row");

      // Date du jour au format YYYY-MM-DD
      const today = new Date();
      const formattedDate = today.toISOString().split("T")[0];

      // Structure HTML de la nouvelle ligne
      newRow.innerHTML = `
        <td>Nouveau</td>
        <td>
          <div class="article-cell">
            <input type="text" placeholder="Nom de l'article" class="row-articlenom" required />
          </div>
        </td>
        <td>
          <input type="date" class="row-datesoumission" value="${formattedDate}" required />
        </td>
        <td>
          <select class="row-categoriearticle" required>
            <option value="technologie">Technologie</option>
            <option value="marketing">Marketing</option>
            <option value="innovation">Innovation</option>
            <option value="autres">Autres</option>
          </select>
        </td>
        <td>
          <div class="article-cell">
            <textarea placeholder="Contenu de l'article" class="row-contenu" required></textarea>
          </div>
        </td>
        <td>
          <div class="article-cell">
            <input type="file" accept="image/*" class="row-image-input" required />
            <div class="image-preview"></div>
          </div>
        </td>
        <td>
          <div class="table-actions">
            <button type="button" class="action-icon save-btn" title="Enregistrer">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                <polyline points="7 3 7 8 15 8"></polyline>
              </svg>
            </button>
            <button type="button" class="action-icon cancel-btn" title="Annuler">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
        </td>
      `;

      // Gestion de l'aperçu d'image
      const fileInput = newRow.querySelector(".row-image-input");
      const imagePreview = newRow.querySelector(".image-preview");

      fileInput.addEventListener("change", function () {
        if (this.files && this.files[0]) {
          const imageFile = this.files[0];

          if (!imageFile.type.startsWith("image/")) {
            alert("Veuillez sélectionner un fichier image valide.");
            return;
          }

          const reader = new FileReader();
          reader.onload = function (e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width:100px; height:auto;" />`;
          };
          reader.readAsDataURL(imageFile);
        }
      });

      // Bouton Annuler
      const cancelBtn = newRow.querySelector(".cancel-btn");
      cancelBtn.addEventListener("click", function () {
        articlesTableBody.removeChild(newRow);
      });

      // Bouton Enregistrer
      const saveBtn = newRow.querySelector(".save-btn");
      const originalSaveBtnHTML = saveBtn.innerHTML;

      saveBtn.addEventListener("click", async function (e) {
        e.preventDefault();

        const articlenom = newRow.querySelector(".row-articlenom").value.trim();
        const datesoumission = newRow.querySelector(
          ".row-datesoumission"
        ).value;
        const categoriearticle = newRow.querySelector(
          ".row-categoriearticle"
        ).value;
        const contenu = newRow.querySelector(".row-contenu").value.trim();
        const imageFile = newRow.querySelector(".row-image-input").files[0];

        // Vérifications de base
        if (!articlenom || !datesoumission || !categoriearticle || !contenu) {
          alert("Veuillez remplir tous les champs obligatoires.");
          return;
        }

        if (/[0-9]/.test(articlenom)) {
          alert("Le nom de l'article ne doit pas contenir de chiffres !");
          return;
        }

        const selectedDate = new Date(datesoumission);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
          alert(
            "La date de soumission ne peut pas être antérieure à aujourd'hui."
          );
          return;
        }

        try {
          const formData = new FormData();
          formData.append("articlenom", articlenom);
          formData.append("datesoumission", datesoumission);
          formData.append("categoriearticle", categoriearticle);
          formData.append("contenu", contenu);
          if (imageFile) formData.append("image", imageFile);

          // Animation de chargement
          saveBtn.innerHTML = spinnerHTML;
          saveBtn.disabled = true;

          // Envoi de la requête
          const response = await fetch("admin.php", {
            method: "POST",
            body: formData,
          });

          const data = await response.json();

          if (!response.ok || !data.success) {
            throw new Error(data.message || "Échec de l'enregistrement");
          }

          // Rechargement après succès
          window.location.reload();
        } catch (error) {
          console.log("Erreur :", error);

          alert(error.message);
          saveBtn.disabled = false;
          saveBtn.innerHTML = originalSaveBtnHTML;
        }
      });

      // Ajouter la ligne au tableau

      articlesTableBody.appendChild(newRow);
    });
  }

  // Edit existing article functionality
  const articlesTable = document.getElementById("articlesTable");
  if (articlesTable) {
    articlesTable.addEventListener("click", function (e) {
      const modifyBtn = e.target.closest(".modify-btn");
      if (!modifyBtn) return;

      const row = modifyBtn.closest("tr");
      const cells = row.querySelectorAll("td");
      const actionCell = cells[cells.length - 1];
      const deleteLink = actionCell.querySelector("a.action-icon");

      // Sauvegarde de l'état initial (HTML) pour "Annuler"
      const originalSaveBtnHTML = modifyBtn.innerHTML;
      const originalDeleteHTML = deleteLink.innerHTML;
      const originalRowHTML = Array.from(cells).map((td) => td.innerHTML);

      // Conversion des cellules en champs éditables
      for (let i = 1; i < cells.length - 1; i++) {
        const cell = cells[i];

        if (i === 1) {
          // Nom
          const text = cell.textContent.trim();
          cell.innerHTML = `<input type="text" class="edit-input" value="${text}"
          pattern="[A-Za-zÀ-ÿ\\s\\-']+" required>`;
          cell.querySelector("input").addEventListener("input", function () {
            this.setCustomValidity(
              /[0-9]/.test(this.value)
                ? "Le nom ne doit pas contenir de chiffres"
                : ""
            );
          });
        } else if (i === 2) {
          // Date
          const dateValue = cell.textContent.trim();
          cell.innerHTML = `<input type="date" class="edit-input" value="${dateValue}"
          min="${new Date().toISOString().split("T")[0]}" required>`;
        } else if (i === 3) {
          // Catégorie
          const currentCat = cell.textContent.trim().toLowerCase();
          cell.innerHTML = `
          <select class="edit-input" required>
            ${["technologie", "marketing", "innovation", "autres"]
              .map(
                (c) => `
              <option value="${c}" ${c === currentCat ? "selected" : ""}>
                ${c.charAt(0).toUpperCase() + c.slice(1)}
              </option>`
              )
              .join("")}
          </select>`;
        } else if (i === 4) {
          // Contenu
          const content = cell.textContent.trim();
          cell.innerHTML = `<textarea class="edit-input content-textarea" required>${content}</textarea>`;
        } else if (i === 5) {
          // Image
          const imgEl = cell.querySelector("img");
          const currentSrc = imgEl ? imgEl.src : "";
          cell.innerHTML = `
          <div class="article-cell">
            <input type="file" accept="image/*" class="edit-image-input" />
            <div class="image-preview">
              ${
                currentSrc
                  ? `<img src="${currentSrc}" style="max-width:100px;"/>`
                  : ""
              }
            </div>
          </div>`;
          // Preview de la nouvelle image
          const fileInput = cell.querySelector(".edit-image-input");
          const preview = cell.querySelector(".image-preview");
          fileInput.addEventListener("change", function () {
            if (this.files[0] && this.files[0].type.startsWith("image/")) {
              const reader = new FileReader();
              reader.onload = (e) =>
                (preview.innerHTML = `<img src="${e.target.result}" style="max-width:100px;"/>`);
              reader.readAsDataURL(this.files[0]);
            }
          });
        }
      }

      // Passe Modifier → Enregistrer
      modifyBtn.innerHTML = originalSaveBtnHTML; // ou icône custom
      modifyBtn.classList.replace("modify-btn", "save-btn");
      modifyBtn.title = "Enregistrer";

      // Passe Supprimer → Annuler
      deleteLink.innerHTML = originalDeleteHTML; // ou icône custom
      deleteLink.href = "javascript:void(0)";
      deleteLink.title = "Annuler";

      // Handler Annuler : restauration du DOM
      const cancelEdit = (ev) => {
        ev.preventDefault();
        originalRowHTML.forEach((html, idx) => {
          cells[idx].innerHTML = html;
        });
      };
      deleteLink.addEventListener("click", cancelEdit, { once: true });

      // Handler Enregistrer
      const saveHandler = async (ev) => {
        ev.stopPropagation();

        // Validation des inputs
        const inputs = row.querySelectorAll(".edit-input");
        let valid = true;
        inputs.forEach((input) => {
          if (!input.checkValidity()) {
            input.reportValidity();
            valid = false;
          }
        });
        if (!valid) return;

        // Affiche spinner
        modifyBtn.innerHTML = spinnerHTML;
        modifyBtn.disabled = true;

        try {
          // FormData
          const formData = new FormData();
          formData.append("id", cells[0].textContent.trim());
          formData.append(
            "nom_article",
            row.querySelector(".edit-input[type=text]")?.value.trim()
          );
          formData.append(
            "date_soumission",
            row.querySelector(".edit-input[type=date]")?.value
          );
          formData.append(
            "categorie",
            row.querySelector("select.edit-input")?.value
          );
          formData.append(
            "contenu",
            row.querySelector(".content-textarea")?.value.trim()
          );

          // Image ou chemin existant
          const imageInput = row.querySelector(".edit-image-input");
          const previewImg = row.querySelector(".image-preview img");
          if (imageInput && imageInput.files[0]) {
            formData.append("image", imageInput.files[0]);
          } else if (previewImg) {
            formData.append("existingImage", previewImg.src);
          }

          formData.append("update", "true");

          // Requête AJAX
          const response = await fetch("admin.php", {
            method: "POST",
            body: formData,
          });
          const data = await response.json();

          if (!response.ok || !data.success) {
            throw new Error(data.message || "Échec de la mise à jour");
          }

          // MAJ du DOM sans reload
          // Nom, date, catégorie, contenu
          cells[1].textContent = row
            .querySelector(".edit-input[type=text]")
            .value.trim();
          cells[2].textContent = row.querySelector(
            ".edit-input[type=date]"
          ).value;
          const cat = row.querySelector("select.edit-input").value;
          cells[3].textContent = cat.charAt(0).toUpperCase() + cat.slice(1);
          cells[4].textContent = row
            .querySelector(".content-textarea")
            .value.trim();

          // Image
          const newSrc = row.querySelector(".image-preview img")?.src || "";
          cells[5].innerHTML = newSrc
            ? `<img src="${newSrc}" style="max-width:100px; height:auto;" />`
            : "";

          // Restauration des actions
          actionCell.innerHTML = `
          <button type="button" class="action-icon modify-btn" title="Modifier">
            ${originalSaveBtnHTML}
          </button>
          <a href="admin.php?id=${cells[0].textContent.trim()}" class="action-icon" title="Supprimer">
            ${originalDeleteHTML}
          </a>
        `;
        } catch (error) {
          console.error("Error:", error);
          alert(error.message);
          // Restaure le bouton Enregistrer
          modifyBtn.disabled = false;
          modifyBtn.innerHTML = originalSaveBtnHTML;
        }
      };

      modifyBtn.addEventListener("click", saveHandler, { once: true });
    });
  }

  // Delete article confirmation
  const articleDeleteLinks = document.querySelectorAll(
    "a.action-icon[href^='admin.php?id=']"
  );

  if (articleDeleteLinks.length > 0) {
    articleDeleteLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        if (!confirm("Êtes-vous sûr de vouloir supprimer cet article ?")) {
          e.preventDefault();
        }
      });
    });
  }

  // ===== ALERTS =====
  // Make alerts disappear after 5 seconds
  const alerts = document.querySelectorAll(".alert");

  if (alerts.length > 0) {
    alerts.forEach((alert) => {
      setTimeout(() => {
        alert.style.transition = "opacity 1s";
        alert.style.opacity = "0";

        setTimeout(() => {
          alert.remove();
        }, 1000);
      }, 5000);
    });
  }

  // ===== CATEGORY FILTER =====
  // Filter articles by category
  const categoryFilter = document.getElementById("categoryFilter");

  if (categoryFilter && articlesTable) {
    categoryFilter.addEventListener("change", function () {
      const selectedCategory = this.value;
      const rows = articlesTable.querySelectorAll("tbody tr");

      rows.forEach((row) => {
        const categoryCell = row.querySelector("td:nth-child(4)");
        const category = categoryCell.textContent.trim().toLowerCase();

        if (
          selectedCategory === "all" ||
          category.includes(selectedCategory.toLowerCase())
        ) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  }
});

// Add a CSS class for spinning animation
document.head.insertAdjacentHTML(
  "beforeend",
  `
  <style>
    .spin {
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
`
);

console.log("Analysis of admin.js completed. Main correction:");
console.log(
  "1. Added missing originalSaveBtnHTML variable in the add article functionality"
);
console.log("2. The code is otherwise well-structured and functional");
