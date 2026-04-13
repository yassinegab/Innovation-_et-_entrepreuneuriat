document.addEventListener("DOMContentLoaded", () => {
  console.log("Initialisation des notifications...");

  const notificationBtn = document.querySelector(".notification-btn");
  const notificationPanel = document.querySelector(".notification-panel");
  const notificationBadge = document.querySelector(".notification-badge");
  const overlay = document.querySelector(".overlay");

  let notificationList = document.querySelector(".notification-list");
  let markAllReadBtn = document.querySelector(".mark-all-read");
  

  // Vérification de l'existence des éléments
  if (!notificationBtn || !notificationBadge || !overlay) {
    console.error("Éléments de notification de base manquants");
    return;
  }

  // Création du panneau si inexistant
  if (!notificationPanel) {
    console.log("Création du panneau de notification");

    const panel = document.createElement("div");
    panel.className = "notification-panel";

    const header = document.createElement("div");
    header.className = "notification-header";
    header.innerHTML = `
      <h3>Notifications</h3>
      <button class="mark-all-read">Tout marquer comme lu</button>
    `;

    notificationList = document.createElement("div");
    notificationList.className = "notification-list";

    panel.appendChild(header);
    panel.appendChild(notificationList);
    document.body.appendChild(panel);
  }

  // Réassigner après éventuelle création
  markAllReadBtn = document.querySelector(".mark-all-read");

  if (!notificationList || !markAllReadBtn) {
    console.error("Éléments du panneau de notification manquants");
    return;
  }

  // Ouvrir/fermer le panneau
  notificationBtn.addEventListener("click", (e) => {
    e.preventDefault();
    console.log("Bouton notification cliqué");

    notificationPanel.classList.toggle("active");
    overlay.classList.toggle("active");

    if (notificationPanel.classList.contains("active")) {
      chargerNotifications();
    }
  });

  // Fermer en cliquant sur l'overlay
  overlay.addEventListener("click", () => {
    notificationPanel.classList.remove("active");
    overlay.classList.remove("active");
  });

  // Charger les notifications
  function chargerNotifications() {
    console.log("Chargement des notifications...");
    notificationList.innerHTML = `<div class="notification-loading">Chargement des notifications...</div>`;

    fetch("../../api/get-notifications.php")
      .then((response) => {
        if (!response.ok) throw new Error("Erreur HTTP: " + response.status);
        return response.json();
      })
      .then((data) => {
        console.log("Données reçues:", data);

        // Badge
        notificationBadge.textContent = data.nonLues || 0;
        notificationBadge.style.display = data.nonLues > 0 ? "flex" : "none";

        // Aucune notification
        if (!Array.isArray(data.notifications) || data.notifications.length === 0) {
          notificationList.innerHTML = `<div class="notification-empty">Aucune notification</div>`;
          return;
        }

        notificationList.innerHTML = "";

        data.notifications.forEach((notif) => {
          const item = document.createElement("div");
          item.className = "notification-item";
          if (notif.lu == 0) item.classList.add("unread");

          let iconClass = "fas fa-bell";
          if (notif.type === "reponse") iconClass = "fas fa-comment-dots";
          else if (notif.type === "assignation") iconClass = "fas fa-user-plus";
          else if (notif.type === "statut") iconClass = "fas fa-check-circle";

          item.innerHTML = `
            <div class="notification-icon">
              <i class="${iconClass}"></i>
            </div>
            <div class="notification-content">
              <p>${notif.message}</p>
              <span class="notification-time">${notif.date_notification || "Récemment"}</span>
            </div>
          `;

          item.addEventListener("click", () => {
            window.location.href = "reponseList.php?id_consultation=" + notif.id_consultation;
          });

          notificationList.appendChild(item);
        });
      })
      .catch((error) => {
        console.error("Erreur:", error);
        notificationList.innerHTML = `<div class="notification-error">Erreur lors du chargement: ${error.message}</div>`;
      });
  }

  // Marquer toutes comme lues
  markAllReadBtn.addEventListener("click", () => {
    fetch("../../api/marquer-notifications-lues.php")
      .then((response) => {
        if (!response.ok) throw new Error("Erreur HTTP: " + response.status);
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          document.querySelectorAll(".notification-item.unread").forEach((n) => n.classList.remove("unread"));
          notificationBadge.textContent = "0";
          notificationBadge.style.display = "none";
        }
      })
      .catch((error) => console.error("Erreur:", error));
  });

  // Vérification initiale
  function verifierNotifications() {
    fetch("../../api/get-notification-count.php")
      .then((response) => {
        if (!response.ok) throw new Error("Erreur HTTP: " + response.status);
        return response.json();
      })
      .then((data) => {
        notificationBadge.textContent = data.count || 0;
        notificationBadge.style.display = data.count > 0 ? "flex" : "none";
      })
      .catch((error) => console.error("Erreur:", error));
  }

  verifierNotifications();
  setInterval(verifierNotifications, 30000);
});
