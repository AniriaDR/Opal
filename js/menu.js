document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.category-nav a');
    const menuItems = document.querySelectorAll('#menu-items .item');
  
    navLinks.forEach(link => {
      link.addEventListener('click', event => {
        event.preventDefault();
        const category = event.target.getAttribute('data-category');
  
        menuItems.forEach(item => {
          if (category === 'all' || item.getAttribute('data-category') === category) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  });
  