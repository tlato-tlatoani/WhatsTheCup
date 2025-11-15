document.addEventListener("DOMContentLoaded", () => {
  fetch("/WhatsTheCup/app/views/SidebarAdmin.php")
    .then(response => response.text())
    .then(html => {
      const container = document.getElementById("sidebar-container");
      container.innerHTML = html;

    })
    .catch(err => console.error('Error cargando sidebar:', err));
});

