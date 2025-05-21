
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (menuToggle && sidebar) {
      menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
      });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      if (window.innerWidth < 992 && 
          sidebar && 
          sidebar.classList.contains('active') && 
          !sidebar.contains(event.target) && 
          !menuToggle.contains(event.target)) {
        sidebar.classList.remove('active');
      }
    });
    
    // Navigation active state
    const navItems = document.querySelectorAll('.nav-item a');
    
    if (navItems.length > 0) {
      navItems.forEach(item => {
        item.addEventListener('click', function(e) {
          // Remove active class from all items
          document.querySelectorAll('.nav-item').forEach(navItem => {
            navItem.classList.remove('active');
          });
          
          // Add active class to clicked item's parent
          this.parentElement.classList.add('active');
          
          // Update breadcrumb
          const breadcrumbText = document.querySelector('.breadcrumb span:last-child');
          if (breadcrumbText) {
            breadcrumbText.textContent = this.querySelector('span').textContent;
          }
          
          // If on mobile, close the sidebar
          if (window.innerWidth < 992 && sidebar) {
            sidebar.classList.remove('active');
          }
        });
      });
    }
    
    // Chart period buttons
    const chartButtons = document.querySelectorAll('.chart-actions .btn-text');
    
    if (chartButtons.length > 0) {
      chartButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all buttons
          chartButtons.forEach(btn => btn.classList.remove('active'));
          
          // Add active class to clicked button
          this.classList.add('active');
          
          // Here you would typically update the chart data
          // For this demo, we'll just log the selected period
          console.log('Selected period:', this.textContent.trim());
        });
      });
    }
    
    // Table row hover effect
    const tableRows = document.querySelectorAll('.admin-table tbody tr');
    
    if (tableRows.length > 0) {
      tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
          this.style.backgroundColor = 'rgba(255, 255, 255, 0.03)';
        });
        
        row.addEventListener('mouseleave', function() {
          this.style.backgroundColor = '';
        });
      });
    }
    
    // Pagination buttons
    const paginationButtons = document.querySelectorAll('.pagination-btn');
    
    if (paginationButtons.length > 0) {
      paginationButtons.forEach(button => {
        if (!button.disabled) {
          button.addEventListener('click', function() {
            // Remove active class from all buttons
            paginationButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button if it's a number
            if (!this.querySelector('svg')) {
              this.classList.add('active');
            }
            
            // Here you would typically fetch the data for the selected page
            // For this demo, we'll just log the selected page
            const page = this.textContent.trim();
            console.log('Selected page:', page || 'Navigation arrow clicked');
          });
        }
      });
    }
    
    // User profile dropdown (would be expanded in a real implementation)
    const userProfile = document.querySelector('.user-profile');
    
    if (userProfile) {
      userProfile.addEventListener('click', function() {
        console.log('User profile clicked - would show dropdown menu');
      });
    }
    
    // Notifications button (would be expanded in a real implementation)
    const notificationBtn = document.querySelector('.action-btn');
    
    if (notificationBtn) {
      notificationBtn.addEventListener('click', function() {
        console.log('Notifications clicked - would show notifications panel');
      });
    }
    
    // Date filter dropdown (would be expanded in a real implementation)
    const dateFilter = document.querySelector('.date-filter .btn');
    
    if (dateFilter) {
      dateFilter.addEventListener('click', function() {
        console.log('Date filter clicked - would show date range picker');
      });
    }
    
    // Add event button (would be expanded in a real implementation)
    const addEventBtn = document.querySelector('.table-header .btn-primary');
    
    if (addEventBtn) {
      addEventBtn.addEventListener('click', function() {
        console.log('Add event clicked - would show event creation form');
      });
    }
    
    // Table action buttons (would be expanded in a real implementation)
    const actionButtons = document.querySelectorAll('.action-icon');
    
    if (actionButtons.length > 0) {
      actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.stopPropagation();
          const action = this.querySelector('svg').innerHTML.includes('edit') ? 'edit' : 'delete';
          const eventName = this.closest('tr').querySelector('.event-cell span').textContent;
          console.log(`${action} clicked for event: ${eventName}`);
        });
      });
    }
  });