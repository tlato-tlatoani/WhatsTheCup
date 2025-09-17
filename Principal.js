document.addEventListener("DOMContentLoaded", () => {
  fetch("SidebarUsuario.html")
    .then(response => response.text())
    .then(html => {
      const container = document.getElementById("sidebar-container");
      container.innerHTML = html;

      // Ahora que el sidebar está en el DOM, enlazamos el botón
      const btnCrear = document.getElementById("btn-crear-publicacion");
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

function abrirModal() {
  const modal = document.getElementById('modal-publicacion');
  if (!modal) {
    console.warn('No se encontró #modal-publicacion en el DOM');
    return;
  }
  modal.classList.add('show');
  modal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
  const titulo = document.getElementById('titulo');
  if (titulo) titulo.focus();
}

const modal = document.getElementById("modal-publicacion");
const closeBtn = document.getElementById("modal-close");

function cerrarModal() {
  modal.classList.remove("show");
  modal.setAttribute("aria-hidden", "true");
}

// Click en botón cerrar
closeBtn.addEventListener("click", cerrarModal);