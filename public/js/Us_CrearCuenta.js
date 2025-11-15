document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const profileCircle = document.getElementById('profilePicCircle');
    const defaultIcon = profileCircle.querySelector('i'); // Guarda el ícono si existe

    if (fileInput && profileCircle) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Oculta el ícono si existe
                    if (defaultIcon) {
                        defaultIcon.style.display = 'none';
                    }
                    // Establece la imagen como fondo del div
                    profileCircle.style.backgroundImage = `url('${e.target.result}')`;
                    profileCircle.style.backgroundSize = 'cover'; // Asegura que la imagen cubra el div
                    profileCircle.style.backgroundPosition = 'center'; // Centra la imagen
                    profileCircle.style.backgroundRepeat = 'no-repeat';
                    profileCircle.textContent = ''; // Limpia cualquier texto dentro si lo hubiera
                }

                // Lee el archivo como una URL de datos (Base64)
                reader.readAsDataURL(file);
            } else {
                // Si no se selecciona una imagen válida, restaura el estado inicial
                profileCircle.style.backgroundImage = 'none';
                 if (defaultIcon) {
                    defaultIcon.style.display = ''; // Muestra el ícono de nuevo
                } else {
                     profileCircle.textContent = ''; // O limpia el texto si no hay icono
                }
                // Opcional: Mostrar un mensaje de error si el archivo no es una imagen
                console.log("Por favor, selecciona un archivo de imagen válido.");
            }
        });
    } else {
        console.error("No se encontraron los elementos necesarios (input o div) en el DOM.");
    }
});