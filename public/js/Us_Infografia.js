document.addEventListener("DOMContentLoaded", () => {
  fetch("/WhatsTheCup/app/views/SidebarUsuario.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("sidebar-container").innerHTML = data;
    });


    // En tu archivo JavaScript (Ej: Us_Infografia.js o donde manejes la lógica)

    // --- Elementos del DOM ---
    const btnContribuir = document.getElementById('btn-contribuir'); // El botón en Us_Infografia.php
    const modalPublicacion = document.getElementById('modal-publicacion');
    const btnCerrar = document.getElementById('modal-close');
    const backdrop = document.getElementById('modal-backdrop');

    // --- Función Principal ---
    function abrirModalContribuir() {
        // 1. Mostrar el modal usando la clase 'show' definida en tu CSS
        modalPublicacion.classList.add('show');

        // 2. Aquí iría la lógica para obtener y actualizar el título y categorías
        // ... (llama a obtenerDatosModelo() y actualizarVistaModal() como se explicó antes)
    }

    // --- Cerrar Modal ---
    function cerrarModal() {
        modalPublicacion.classList.remove('show');
    }

    // --- Event Listeners ---
    btnContribuir.addEventListener('click', abrirModalContribuir);
    btnCerrar.addEventListener('click', cerrarModal);
    // Opcional: Cerrar al hacer clic en el fondo oscuro
    backdrop.addEventListener('click', cerrarModal);

    // Opcional: Cerrar al presionar la tecla ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalPublicacion.classList.contains('show')) {
            cerrarModal();
        }
    });


    // --- 2. SIMULACIÓN DEL MODELO ---
function obtenerDatosModelo() {
    // **Aquí es donde contactarías a tu API o backend (el Modelo) para obtener los datos**
    // Por ejemplo, usando 'fetch()'
    // fetch('/api/datos-mundial')
    // .then(response => response.json())
    // .then(data => actualizarVistaModal(data));

    // SIMULACIÓN DE DATOS ESTATICOS
    return {
        nombreMundial: "Norteamérica 2026",
        categorias: [
            { valor: "noticia", texto: "Noticias y Novedades" },
            { valor: "estadistica", texto: "Estadísticas Relevantes" },
            { valor: "opinion", texto: "Análisis y Opinión" },
            { valor: "curiosidad", texto: "Datos Curiosos" }
        ]
    };
}

// --- 3. ACTUALIZACIÓN DE LA VISTA ---
function actualizarVistaModal(datos) {
    // a. Título dinámico
    tituloModal.textContent = `Contribuir a ${datos.nombreMundial}`;
    
    // b. Categorías dinámicas
    // Limpiar opciones existentes (excepto la primera "Categoría")
    selectCategoria.innerHTML = '<option value="">Categoría</option>'; 
    
    // Crear e insertar nuevas opciones
    datos.categorias.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.valor;
        option.textContent = cat.texto;
        selectCategoria.appendChild(option);
    });
}
});

