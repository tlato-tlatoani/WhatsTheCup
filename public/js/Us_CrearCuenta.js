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
// --- LÓGICA DE AUTOCOMPLETADO DE CORREO ---
    const commonDomains = [
        'gmail.com',
        'hotmail.com',
        'outlook.com',
        'yahoo.com',
        'icloud.com',
        'live.com',
        'protonmail.com'
    ];

    const emailInput = document.getElementById('CORREO'); 
    const suggestionsBox = document.getElementById('suggestions');

    if (emailInput && suggestionsBox) {
        emailInput.addEventListener('input', function() {
            const inputValue = this.value;
            const atIndex = inputValue.indexOf('@');
            
            suggestionsBox.innerHTML = ''; 

            // Activación: Si existe el '@' y no es el primer carácter 
            if (atIndex <= 0) { 
                suggestionsBox.style.display = 'none';
                return;
            }

            // 1. Separar las partes del correo
            const userPart = inputValue.substring(0, atIndex);
            const domainPart = inputValue.substring(atIndex + 1).toLowerCase();

            // --- 2. LÓGICA DE FILTRADO MODIFICADA ---
            let domainsToShow;
            
            if (domainPart.length === 0) {
                // Si el usuario acaba de escribir solo '@' (dominio vacío), mostramos TODOS los dominios.
                domainsToShow = commonDomains;
            } else {
                // Si ha escrito algo después del '@', filtramos normalmente.
                domainsToShow = commonDomains.filter(domain => 
                    domain.startsWith(domainPart)
                );
            }
            
            // 3. Mostrar sugerencias (usamos domainsToShow)
            if (domainsToShow.length > 0) {
                domainsToShow.forEach(domain => {
                    const li = document.createElement('li');
                    
                    const fullSuggestion = `${userPart}@${domain}`;
                    li.textContent = fullSuggestion;
                    li.dataset.suggestion = fullSuggestion; 
                    
                    li.addEventListener('click', function() {
                        emailInput.value = this.dataset.suggestion;
                        suggestionsBox.style.display = 'none';
                        emailInput.focus(); 
                    });

                    suggestionsBox.appendChild(li);
                });
                suggestionsBox.style.display = 'block'; 
            } else {
                suggestionsBox.style.display = 'none'; 
            }
        });
       
        emailInput.addEventListener('blur', function() {
            setTimeout(() => { suggestionsBox.style.display = 'none'; }, 150); 
        });

        document.addEventListener('click', function(e) {
            if (e.target !== emailInput && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
    }

    const countryInput = document.getElementById('countryInput');
    const countrySuggestionsBox = document.getElementById('countrySuggestions');

    if (countryInput && countrySuggestionsBox && typeof countries !== 'undefined') {
        countryInput.addEventListener('input', function() {
            const inputValue = this.value.trim().toLowerCase();
            countrySuggestionsBox.innerHTML = ''; 

            if (inputValue.length < 1) { // Mínimo 1 carácter para mostrar
                countrySuggestionsBox.style.display = 'none';
                return;
            }

            // Filtrar países que incluyen el texto ingresado
            const filteredCountries = countries.filter(country => 
                country.toLowerCase().startsWith(inputValue)
            );

            if (filteredCountries.length > 0) {
                filteredCountries.forEach(country => {
                    const li = document.createElement('li');
                    li.textContent = country;
                    li.dataset.suggestion = country; 
                    
                    li.addEventListener('click', function() {
                        countryInput.value = this.dataset.suggestion;
                        countrySuggestionsBox.style.display = 'none';
                        countryInput.focus();
                    });

                    countrySuggestionsBox.appendChild(li);
                });
                countrySuggestionsBox.style.display = 'block'; 
            } else {
                countrySuggestionsBox.style.display = 'none'; 
            }
        });

        // Ocultar al perder el foco
        countryInput.addEventListener('blur', function() {
            setTimeout(() => { countrySuggestionsBox.style.display = 'none'; }, 150); 
        });
        
        // Asegurar que si se hace click fuera, se cierre
        document.addEventListener('click', function(e) {
            if (e.target !== countryInput && !countrySuggestionsBox.contains(e.target)) {
                countrySuggestionsBox.style.display = 'none';
            }
        });
    } else {
        // Esto ayudará a depurar si country-data.js no se carga
        console.error("No se encontraron los elementos del país o la lista de países (countries) no está cargada.");
    }

    // NACIONALIDAD 


   // --- Lógica de Autocompletado para Nacionalidad ---

    const nationInput = document.getElementById('nationInput');
    const nationSuggestionsBox = document.getElementById('nationSuggestions');

    if (nationInput && nationSuggestionsBox && typeof nacionalidades !== 'undefined') {
        nationInput.addEventListener('input', function() {
            const inputValue = this.value;
            nationSuggestionsBox.innerHTML = ''; 
            
            // 1. Encontrar la última coma (o el inicio del texto)
            // Se busca la última coma seguida de un espacio o no espacio
            const lastCommaIndex = inputValue.lastIndexOf(','); 
            
            // 2. Extraer la palabra clave actual para la búsqueda
            // Si hay una coma, la clave es lo que sigue (quitando espacios).
            // Si no hay coma, la clave es todo el valor.
            let searchKey;
            
            if (lastCommaIndex !== -1) {
                // Obtener el texto después de la última coma y limpiarlo.
                searchKey = inputValue.substring(lastCommaIndex + 1).trim().toLowerCase();
            } else {
                // Si no hay comas, la clave es el valor completo.
                searchKey = inputValue.trim().toLowerCase();
            }

            // 3. Condición de activación
            if (searchKey.length < 1) { 
                nationSuggestionsBox.style.display = 'none';
                return;
            }

            // 4. Filtrar nacionalidades usando la clave de búsqueda
            const filteredNations = nacionalidades.filter(nation => 
                nation.toLowerCase().startsWith(searchKey)
            );

            const currentNations = inputValue
                .split(',') // Separar por comas
                .map(n => n.trim().toLowerCase()) // Limpiar espacios y pasar a minúsculas
                .filter(n => n.length > 0); // Eliminar entradas vacías (si hay varias comas)


            if (filteredNations.length > 0) {
                filteredNations.forEach(nation => {
                    const li = document.createElement('li');
                    
                    // Comprobar si la sugerencia ya está en la lista actual
                    const isDuplicate = currentNations.includes(nation.toLowerCase());
                    
                    li.textContent = nation;
                    li.dataset.suggestion = nation; 

                    // Opcional: Deshabilitar o estilizar si es duplicado
                    if (isDuplicate) {
                        li.classList.add('duplicate-suggestion');
                        li.title = 'Ya has añadido esta nacionalidad';
                        // No añadimos el evento click si es duplicado para evitar la selección
                    } else {
                        // --- Lógica de Inserción (SOLO si no es duplicado) ---
                        li.addEventListener('click', function() {
                            const selectedNation = this.dataset.suggestion;
                            let newValue;
                            
                            if (lastCommaIndex !== -1) {
                                // Si ya hay comas, reemplazar la última palabra
                                const textBeforeComma = inputValue.substring(0, lastCommaIndex).trim(); 
                                // Se añade un ', ' para que el usuario pueda escribir la siguiente
                                newValue = `${textBeforeComma}, ${selectedNation}`; 
                            } else {
                                // Si no hay comas, solo usa la sugerencia
                                newValue = selectedNation;
                            }
                            
                            nationInput.value = newValue;
                            nationSuggestionsBox.style.display = 'none';
                            nationInput.focus();
                        });
                    } // Fin del else (si no es duplicado)

                    nationSuggestionsBox.appendChild(li);
                });
                nationSuggestionsBox.style.display = 'block'; 
            } else {
                nationSuggestionsBox.style.display = 'none'; 
            }
        
        });

        // Ocultar al perder el foco
        nationInput.addEventListener('blur', function() {
            setTimeout(() => { nationSuggestionsBox.style.display = 'none'; }, 150); 
        });
        
        // Asegurar que si se hace click fuera, se cierre
        document.addEventListener('click', function(e) {
            if (e.target !== nationInput && !nationSuggestionsBox.contains(e.target)) {
                nationSuggestionsBox.style.display = 'none';
            }
        });
    } else {
        console.error("No se encontró el input de Nacionalidad o la lista de nacionalidades no está cargada.");
    }

});