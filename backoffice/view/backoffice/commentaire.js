document.addEventListener("DOMContentLoaded", function () {
  // ===== SIDEBAR AND NAVIGATION =====
  const menuToggle = document.querySelector(".menu-toggle");
  const sidebar = document.querySelector(".admin-sidebar");

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
      
      // Update active state
      document.querySelectorAll(".nav-item").forEach(navItem => navItem.classList.remove("active"));
      this.parentElement.classList.add("active");
      
      // Update breadcrumb
      const breadcrumbText = document.querySelector(".breadcrumb span:last-child");
      if (breadcrumbText) {
        breadcrumbText.textContent = this.querySelector("span").textContent;
      }
      
      // Close sidebar on mobile
      if (window.innerWidth < 992) {
        sidebar.classList.remove("active");
      }
    });
  });

  // ===== COMMENT FILTERING =====
  const commentFilter = document.getElementById("commentFilter");
  const commentsTable = document.getElementById("commentsTable");

  if (commentFilter && commentsTable) {
    commentFilter.addEventListener("change", function() {
      const status = this.value;
      const rows = commentsTable.querySelectorAll("tbody tr");
      
      rows.forEach(row => {
        if (status === "all" || row.dataset.status === status) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  }

  // ===== ARTICLE FILTERING =====
  const articleFilter = document.getElementById("articleFilter");

  if (articleFilter && commentsTable) {
    articleFilter.addEventListener("change", function() {
      const articleId = this.value;
      const rows = commentsTable.querySelectorAll("tbody tr");
      
      rows.forEach(row => {
        const articleCell = row.querySelector("td:nth-child(4)").textContent;
        
        if (articleId === "all" || articleCell.includes(getArticleNameById(articleId))) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  }

  function getArticleNameById(id) {
    const articleNames = {
      "1": "Les tendances entrepreneuriales en 2023",
      "2": "Comment lever des fonds pour votre startup",
      "3": "L'importance du marketing digital",
      "4": "Innovations technologiques pour entrepreneurs"
    };
    
    return articleNames[id] || "";
  }

  // ===== COMMENT ACTIONS =====
  commentsTable?.addEventListener("click", function(e) {
    const btn = e.target.closest(".action-icon");
    if (!btn) return;
    
    const row = btn.closest("tr");
    const commentId = row.querySelector("td:first-child").textContent;
    
    if (btn.classList.contains("approve-btn")) {
      approveComment(commentId, row);
    } else if (btn.classList.contains("delete-btn")) {
      deleteComment(commentId, row);
    } else if (btn.classList.contains("view-btn")) {
      viewComment(commentId);
    }
  });

  function approveComment(id, row) {
    // Simuler une requête API
    showSpinner(row.querySelector(".approve-btn"));
    
    setTimeout(() => {
      // Mettre à jour le statut
      const statusCell = row.querySelector("td:nth-child(6)");
      statusCell.innerHTML = '<span class="table-badge status approved">Approuvé</span>';
      
      // Mettre à jour les actions
      const actionsCell = row.querySelector("td:last-child");
      actionsCell.innerHTML = `
        <div class="table-actions">
          <button class="action-icon view-btn" title="Voir">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11-8"></path>
              <path d="M1 12s4 8 11 8 11-8"></path>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
          </button>
          <button class="action-icon delete-btn" title="Supprimer">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
          </button>
        </div>
      `;
      
      // Mettre à jour l'attribut data-status
      row.dataset.status = "approved";
      
      // Afficher une alerte de succès
      showAlert("Le commentaire a été approuvé avec succès.", "success");
      
      // Mettre à jour les statistiques
      updateStats("approve");
    }, 800);
  }

  function deleteComment(id, row) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ?")) {
      // Simuler une requête API
      showSpinner(row.querySelector(".delete-btn"));
      
      setTimeout(() => {
        // Supprimer la ligne avec animation
        row.style.transition = "opacity 0.3s, transform 0.3s";
        row.style.opacity = "0";
        row.style.transform = "translateX(20px)";
        
        setTimeout(() => {
          row.remove();
          
          // Afficher une alerte de succès
          showAlert("Le commentaire a été supprimé avec succès.", "success");
          
          // Mettre à jour les statistiques
          updateStats("delete", row.dataset.status);
        }, 300);
      }, 800);
    }
  }

  function viewComment(id) {
    // Simuler l'ouverture d'une modal ou redirection
    alert(`Affichage du commentaire #${id}`);
  }

  function showSpinner(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = `
      <svg class="spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"></path>
      </svg>
    `;
    button.disabled = true;
    
    // Ajouter une animation de rotation
    const spinner = button.querySelector(".spinner");
    spinner.style.animation = "spin 1s linear infinite";
    
    // Définir l'animation de rotation
    if (!document.getElementById("spinner-style")) {
      const style = document.createElement("style");
      style.id = "spinner-style";
      style.textContent = `
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    }
  }

  // ===== STATS UPDATES =====
  function updateStats(action, status) {
    const totalStats = document.querySelector(".stat-card:nth-child(1) .stat-value");
    const pendingStats = document.querySelector(".stat-card:nth-child(2) .stat-value");
    const reportedStats = document.querySelector(".stat-card:nth-child(3) .stat-value");
    const approvedStats = document.querySelector(".stat-card:nth-child(4) .stat-value");
    
    let total = parseInt(totalStats.textContent);
    let pending = parseInt(pendingStats.textContent);
    let reported = parseInt(reportedStats.textContent);
    let approved = parseInt(approvedStats.textContent);
    
    if (action === "delete") {
      total--;
      totalStats.textContent = total;
      
      if (status === "pending") {
        pending--;
        pendingStats.textContent = pending;
      } else if (status === "reported") {
        reported--;
        reportedStats.textContent = reported;
      } else if (status === "approved") {
        approved--;
        approvedStats.textContent = approved;
      }
    } else if (action === "approve") {
      approved++;
      approvedStats.textContent = approved;
      
      if (status === "pending") {
        pending--;
        pendingStats.textContent = pending;
      } else if (status === "reported") {
        reported--;
        reportedStats.textContent = reported;
      }
    }
  }

  // ===== CHART PERIOD BUTTONS =====
  const chartButtons = document.querySelectorAll(".chart-actions .btn-text");
  
  if (chartButtons.length > 0) {
    chartButtons.forEach(button => {
      button.addEventListener("click", function() {
        // Remove active class from all buttons
        chartButtons.forEach(btn => btn.classList.remove("active"));
        
        // Add active class to clicked button
        this.classList.add("active");
        
        // Here you would typically update the chart data
        console.log("Selected period:", this.textContent.trim());
      });
    });
  }

  // ===== PAGINATION =====
  const paginationButtons = document.querySelectorAll(".pagination-btn");
  
  if (paginationButtons.length > 0) {
    paginationButtons.forEach(button => {
      if (!button.disabled) {
        button.addEventListener("click", function() {
          // Remove active class from all buttons
          paginationButtons.forEach(btn => btn.classList.remove("active"));
          
          // Add active class to clicked button if it's a number
          if (!this.querySelector("svg")) {
            this.classList.add("active");
          }
          
          // Here you would typically fetch the data for the selected page
          console.log("Selected page:", this.textContent.trim() || "Navigation arrow clicked");
        });
      }
    });
  }

  // ===== ALERTS =====
  function showAlert(message, type) {
    const alertContainer = document.getElementById("alertContainer");
    
    const alert = document.createElement("div");
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    alertContainer.appendChild(alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      alert.style.opacity = "0";
      setTimeout(() => {
        alert.remove();
      }, 300);
    }, 5000);
  }

  // ===== TABLE ROW HOVER EFFECT =====
  const tableRows = document.querySelectorAll(".admin-table tbody tr");
  
  if (tableRows.length > 0) {
    tableRows.forEach(row => {
      row.addEventListener("mouseenter", function() {
        this.style.backgroundColor = "rgba(255, 255, 255, 0.03)";
      });
      
      row.addEventListener("mouseleave", function() {
        this.style.backgroundColor = "";
      });
    });
  }
});