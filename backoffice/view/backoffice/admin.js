document.addEventListener("DOMContentLoaded", function () {
  // Toggle sidebar on mobile
  const menuToggle = document.querySelector(".menu-toggle");
  const sidebar = document.querySelector(".admin-sidebar");

  if (menuToggle && sidebar) {
    menuToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
    });
  }

  // Close sidebar when clicking outside on mobile
  document.addEventListener("click", function (event) {
    if (
      window.innerWidth < 992 &&
      sidebar &&
      sidebar.classList.contains("active") &&
      !sidebar.contains(event.target) &&
      !menuToggle.contains(event.target)
    ) {
      sidebar.classList.remove("active");
    }
  });

  // Navigation active state
  const navItems = document.querySelectorAll(".nav-item a");

  if (navItems.length > 0) {
    navItems.forEach((item) => {
      item.addEventListener("click", function (e) {
        // Remove active class from all items
        document.querySelectorAll(".nav-item").forEach((navItem) => {
          navItem.classList.remove("active");
        });

        // Add active class to clicked item's parent
        this.parentElement.classList.add("active");

        // Update breadcrumb
        const breadcrumbText = document.querySelector(
          ".breadcrumb span:last-child"
        );
        if (breadcrumbText) {
          breadcrumbText.textContent = this.querySelector("span").textContent;
        }

        // If on mobile, close the sidebar
        if (window.innerWidth < 992 && sidebar) {
          sidebar.classList.remove("active");
        }
      });
    });
  }

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
        // For this demo, we'll just log the selected period
        console.log("Selected period:", this.textContent.trim());
      });
    });
  }

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
          // For this demo, we'll just log the selected page
          const page = this.textContent.trim();
          console.log("Selected page:", page || "Navigation arrow clicked");
        });
      }
    });
  }

  // User profile dropdown (would be expanded in a real implementation)
  const userProfile = document.querySelector(".user-profile");

  if (userProfile) {
    userProfile.addEventListener("click", function () {
      console.log("User profile clicked - would show dropdown menu");
    });
  }

  // Notifications button (would be expanded in a real implementation)
  const notificationBtn = document.querySelector(".action-btn");

  if (notificationBtn) {
    notificationBtn.addEventListener("click", function () {
      console.log("Notifications clicked - would show notifications panel");
    });
  }

  // Date filter dropdown (would be expanded in a real implementation)
  const dateFilter = document.querySelector(".date-filter .btn");

  if (dateFilter) {
    dateFilter.addEventListener("click", function () {
      console.log("Date filter clicked - would show date range picker");
    });
  }

  // Add event button (would be expanded in a real implementation)

  // Table action buttons (would be expanded in a real implementation)
});
document.addEventListener("DOMContentLoaded", function () {
  const addArticleBtn = document.getElementById("addArticleBtn");
  const articlesTable = document
    .getElementById("articlesTable")
    .querySelector("tbody");

  addArticleBtn.addEventListener("click", function () {
    // Create a new row
    const newRow = document.createElement("tr");

    // Add cells to the row
    newRow.innerHTML = `
      <td>New</td>
      <td>
        <div class="article-cell">
          <input type="text" placeholder="Nom de l'article" class="row-articlenom" required />
        </div>
      </td>
      <td>
        <input type="date" class="row-datesoumission" required />
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
        <div class="table-actions">
          <button type="button" class="action-icon save-btn">
            ✅
          </button>
          <button type="button" class="action-icon delete-btn">
            ❌
          </button>
        </div>
      </td>
    `;

    articlesTable.appendChild(newRow);

    // Add event listener to the "Confirm" button in the new row
    const saveBtn = newRow.querySelector(".save-btn");
    saveBtn.addEventListener("click", function () {
      // Get the values from the row inputs
      const articlenom = newRow.querySelector(".row-articlenom").value;
      const datesoumission = newRow.querySelector(".row-datesoumission").value;
      const categoriearticle = newRow.querySelector(
        ".row-categoriearticle"
      ).value;

      // Validate inputs
      if (!articlenom || !datesoumission || !categoriearticle) {
        alert("Veuillez remplir tous les champs.");
        return;
      }

      // Dynamically create a form
      const dynamicForm = document.createElement("form");
      dynamicForm.action = "admin.php";
      dynamicForm.method = "POST";
      dynamicForm.style.display = "none"; // Hide the form

      // Add hidden inputs to the form
      dynamicForm.innerHTML = `
        <input type="hidden" name="articlenom" value="${articlenom}" />
        <input type="hidden" name="datesoumission" value="${datesoumission}" />
        <input type="hidden" name="categoriearticle" value="${categoriearticle}" />
      `;

      // Append the form to the body
      document.body.appendChild(dynamicForm);

      // Submit the form
      dynamicForm.submit();

      // Remove the form after submission
      document.body.removeChild(dynamicForm);
    });

    // Add event listener to the "Delete" button to remove the row
    const deleteBtn = newRow.querySelector(".delete-btn");
    deleteBtn.addEventListener("click", function () {
      newRow.remove();
      document.body.removeChild(dynamicForm);
    });
  });
});
// document.addEventListener("DOMContentLoaded", function () {
//   const addArticleBtn = document.getElementById("addArticleBtn"); // Button to add an article
//   const addArticleForm = document.getElementById("addArticleForm"); // The form template
//   const cancelArticleBtn = document.getElementById("cancelArticleBtn"); // Cancel button in the form

//   // Show the form when "Ajouter un article" is clicked
//   addArticleBtn.addEventListener("click", function () {
//     console.log("Ajouter un article button clicked");
//     addArticleForm.style.display = "block"; // Display the form
//   });

//   // Hide the form when "Annuler" is clicked
//   cancelArticleBtn.addEventListener("click", function () {
//     console.log("Cancel button clicked");
//     addArticleForm.style.display = "none"; // Hide the form
//     document.getElementById("articleForm").reset(); // Reset the form fields
//   });
// });
document.addEventListener("DOMContentLoaded", function () {
  const articlesTable = document.getElementById("articlesTable");

  articlesTable.addEventListener("click", function (e) {
    const modifyBtn = e.target.closest(".modify-btn");
    if (!modifyBtn) return;

    const row = modifyBtn.closest("tr");
    const cells = row.querySelectorAll("td");
    const actionCell = cells[cells.length - 1];
    const deleteLink = actionCell.querySelector("a.action-icon");

    // Convert cells to editable inputs
    for (let i = 1; i < cells.length - 1; i++) {
      const cell = cells[i];
      if (i === 1) {
        const span = cell.querySelector("span");
        cell.innerHTML = `<input type="text" value="${span.textContent}" class="edit-input">`;
      } else if (i === 2) {
        const dateValue = cell.textContent.trim();
        cell.innerHTML = `<input type="date" value="${dateValue}" class="edit-input">`;
      } else if (i === 3) {
        const currentCategory = cell
          .querySelector(".table-badge")
          ?.textContent.trim();
        cell.innerHTML = `
                  <select class="category-select">
                      <option value="technologie" ${
                        currentCategory === "technologie" ? "selected" : ""
                      }>Technologie</option>
                      <option value="marketing" ${
                        currentCategory === "marketing" ? "selected" : ""
                      }>Marketing</option>
                      <option value="innovation" ${
                        currentCategory === "innovation" ? "selected" : ""
                      }>Innovation</option>
                      <option value="autres" ${
                        currentCategory === "autres" ? "selected" : ""
                      }>Autres</option>
                  </select>`;
      }
    }

    // Update buttons
    modifyBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 6L9 17l-5-5"/>
          </svg>`;
    modifyBtn.classList.replace("modify-btn", "save-btn");

    deleteLink.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>`;
    deleteLink.href = "javascript:void(0);";

    // Event handlers
    const cancelEdit = (e) => {
      e.preventDefault();
      window.location.reload(); // Use reload instead of href change
    };

    const saveHandler = async (e) => {
      e.stopPropagation();
      const inputs = row.querySelectorAll(".edit-input, .category-select");

      try {
        const response = await fetch("admin.php", {
          method: "POST",
          body: new URLSearchParams({
            id: cells[0].textContent.trim(),
            nom_article: inputs[0].value,
            date_soumission: inputs[1].value,
            categorie: inputs[2].value,
            update: "true",
          }),
        });

        window.location.reload(); // Always refresh after submission
      } catch (error) {
        console.error("Error:", error);
        window.location.reload();
      }
    };

    deleteLink.addEventListener("click", cancelEdit);
    modifyBtn.addEventListener("click", saveHandler, { once: true });
  });
});
function createDateInput() {
  const dateInput = document.createElement("input");
  dateInput.type = "date";
  dateInput.min = new Date().toISOString().split("T")[0]; // Date minimum = aujourd'hui
  return dateInput;
}

function validateNameInput(input) {
  const regex = /^[a-zA-ZÀ-ÿ\s\-']+$/;
  if (!regex.test(input.value)) {
    input.setCustomValidity("Le nom ne doit pas contenir de chiffres");
  } else {
    input.setCustomValidity("");
  }
}

// Modifier la création des inputs
cells[1].innerHTML = `
  <input type="text" 
         class="edit-input" 
         oninput="validateNameInput(this)"
         pattern="[a-zA-ZÀ-ÿ\s\-']+"
         required>`;
// Faire disparaître les alertes après 5 secondes
document.addEventListener('DOMContentLoaded', () => {
  const alerts = document.querySelectorAll('.alert');

  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 1s';
      alert.style.opacity = '0';

      setTimeout(() => {
        alert.remove();
      }, 1000);
    }, 5000);
  });
});