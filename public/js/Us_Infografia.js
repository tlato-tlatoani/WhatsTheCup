document.addEventListener("DOMContentLoaded", () => {
  fetch("/WhatsTheCup/app/views/SidebarUsuario.php")
    .then(response => response.text())
    .then(data => {
      document.getElementById("sidebar-container").innerHTML = data;
    });


const modalPublicacion = document.getElementById('modal-publicacion');
const tituloModal = document.getElementById('titulo-modal');
const selectCategoria = document.getElementById('categoria');
const btnCerrar = document.getElementById('modal-close');
const backdrop = document.getElementById('modal-backdrop');


/**
 * Función principal para abrir el modal, trayendo datos del backend.
 * @param {string} idMundial - El ID del mundial actual.
 */
async function abrirModalContribuir(idMundial) {
    // Mostrar el modal (usando la clase 'show' que ya tienes en CSS)
    modalPublicacion.classList.add('show');
    
    //  Simula la URL de tu endpoint PHP
    const endpointUrl = `/WhatsTheCup/index.php?route=ajax_mundial_modal&id=${idMundial}`;

    try {
        // 3. Llamada Asíncrona (Fetch) al backend
        const response = await fetch(endpointUrl);
        if (!response.ok) {
            throw new Error('Error al obtener los datos del servidor.');
        }
        
        const data = await response.json();

        if (data.success) {
            // Actualizar la vista con los datos recibidos
            actualizarVistaModal(data);
        } else {
            // Manejar error en la respuesta del servidor
            console.error('Error del servidor:', data.message);
            tituloModal.textContent = 'Error al cargar los datos';
        }

    } catch (error) {
        console.error('Error de red o procesamiento:', error);
        tituloModal.textContent = 'Error de conexión.';
    }
}


/**
 * 5. Actualización de la Vista (Modal)
 */
function actualizarVistaModal(datos) {
    // a. Título dinámico
    tituloModal.textContent = `Contribuir a ${datos.nombre_mundial}`;

    // b. Categorías dinámicas
    // Limpiar opciones existentes y añadir la opción por defecto
    selectCategoria.innerHTML = '<option value="">Categoría</option>'; 
    
    datos.categorias.forEach(cat => {
        const option = document.createElement('option');
        // El 'valor' y el 'texto' dependen de la estructura que devuelva Model_Categorias
        // Asumiendo que devuelve 'id' o 'nombre'
        option.value = cat.id;     
        option.textContent = cat.nombre; 
        selectCategoria.appendChild(option);
    });
}


const urlParams = new URLSearchParams(window.location.search);
const mundialId = urlParams.get('id'); // Obtiene el valor de 'id' de la URL

document.getElementById('btn-contribuir').addEventListener('click', () => {
    if (mundialId) {
        abrirModalContribuir(mundialId);
    } else {
        alert("Error: No se encontró el ID del mundial.");
    }
});

function cerrarModal() {
    if (modalPublicacion) {
        modalPublicacion.classList.remove('show');
    }
}
    
    // Cerrar botón ✕
    if (btnCerrar) {
        // Pasa la referencia a la función cerrarModal, NO al elemento DOM
        btnCerrar.addEventListener('click', cerrarModal); 
    }
    
    // Cerrar fondo oscuro
    if (backdrop) {
        backdrop.addEventListener('click', cerrarModal); 
    }

    // 3. Cerrar con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalPublicacion && modalPublicacion.classList.contains('show')) {
            cerrarModal();
        }
    });

    document.querySelectorAll('.multimedia').forEach(btn => {
    btn.addEventListener('click', e => {
        const tipo = btn.dataset.tipo; // 'imagen' o 'video'
        if(tipo === 'imagen') {
            document.getElementById('input-imagen').click();
        } else {
            document.getElementById('input-video').click();
        }
    });
    });

const form = document.getElementById('form-publicacion');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form); 
    formData.append('btn_crear_publicacion', '1');
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
            alert('Publicación creada correctamente');
            form.reset();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (err) {
        console.error(err);
        alert('Error de conexión');
    }
});



});

