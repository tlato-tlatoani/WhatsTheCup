// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=basic]');

// initialize Tagify on the above input node reference
new Tagify(input)

document.addEventListener("DOMContentLoaded", () => {
  fetch("SidebarAdmin.html")
    .then(response => response.text())
    .then(html => {
      const container = document.getElementById("sidebar-container");
      container.innerHTML = html;

    })
    .catch(err => console.error('Error cargando sidebar:', err));
});
