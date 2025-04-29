document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-projects');
    const suggestionsDiv = document.getElementById('suggestions');
    const cards = document.querySelectorAll('.project-card');
  
    searchInput.addEventListener('input', function () {
      const filter = this.value.toLowerCase();
      const domaines = [];
  
      // 🔥 Récupérer les domaines existants
      cards.forEach(card => {
        const domaineText = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
        const domaineOnly = domaineText.replace('domaine :', '').trim();
  
        if (domaineOnly.startsWith(filter) && !domaines.includes(domaineOnly)) {
          domaines.push(domaineOnly);
        }
      });
  
      // 🔥 Afficher suggestions
      suggestionsDiv.innerHTML = '';
      if (filter.length > 0 && domaines.length > 0) {
        const list = document.createElement('div');
        list.style.background = '#222';
        list.style.border = '1px solid #555';
        list.style.borderRadius = '8px';
        list.style.position = 'absolute';
        list.style.width = searchInput.offsetWidth + 'px';
        list.style.zIndex = '1000';
        list.style.marginTop = '5px';
  
        domaines.forEach(domaine => {
          const item = document.createElement('div');
          item.textContent = domaine;
          item.style.padding = '8px 12px';
          item.style.cursor = 'pointer';
          item.style.color = 'white';
          item.style.fontSize = '14px';
  
          item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#444';
          });
          item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '#222';
          });
  
          item.addEventListener('click', function () {
            searchInput.value = domaine;
            suggestionsDiv.innerHTML = '';
            filterProjects(domaine);
          });
  
          list.appendChild(item);
        });
  
        suggestionsDiv.appendChild(list);
      }
  
      // 🔥 Filtrer les projets pendant que tu écris (même sans clic)
      filterProjects(filter);
    });
  
    document.addEventListener('click', function (e) {
      if (!suggestionsDiv.contains(e.target) && e.target !== searchInput) {
        suggestionsDiv.innerHTML = '';
      }
    });
  
    // ✨ Fonction qui filtre les projets
    function filterProjects(filter) {
      cards.forEach(card => {
        const domaineText = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
        const domaineOnly = domaineText.replace('domaine :', '').trim();
  
        if (filter === '' || domaineOnly.startsWith(filter)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }
  });