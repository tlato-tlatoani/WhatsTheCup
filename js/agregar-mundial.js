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

      // Ahora que el sidebar está en el DOM, enlazamos el botón
      const btnCrear = document.getElementById("AgregarMundial");
      if (btnCrear) {
        btnCrear.addEventListener('click', () => {
          // Llama a la función que abre el modal. Debe existir en scope global.
          if (typeof abrirModal === 'function') {
            abrirModal();
          } else {
            console.warn('abrirModal no está definida');
          }
        });
      }
    })
    .catch(err => console.error('Error cargando sidebar:', err));
});
