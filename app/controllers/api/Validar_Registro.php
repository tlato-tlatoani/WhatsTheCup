<?php
// /app/controllers/api/Validar-Registro.php

header('Content-Type: application/json'); // Indicar que la respuesta es JSON

// --- Configuración ---
define('EDAD_MINIMA', 12); // Edad mínima requerida

// --- Funciones de Validación ---

/**
 * Valida la contraseña según los criterios especificados.

 * @param string $contrasenna La contraseña a validar.
 * @return array Arreglo asociativo ['valido' => bool, 'mensaje' => string]
 */
function validarContrasenna(string $contrasenna): array {
    $mensajeError = '';
    $esValida = true;

    // 1. Validar longitud mínima
    if (strlen($contrasenna) < 8) {
        $mensajeError .= 'La contraseña debe tener al menos 8 caracteres. ';
        $esValida = false;
    }

    // 2. Validar al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $contrasenna)) {
        $mensajeError .= 'La contraseña debe contener al menos una letra mayúscula. ';
        $esValida = false;
    }

    // 3. Validar al menos una letra minúscula
    if (!preg_match('/[a-z]/', $contrasenna)) {
        $mensajeError .= 'La contraseña debe contener al menos una letra minúscula. ';
        $esValida = false;
    }

    // 4. Validar al menos un número
    if (!preg_match('/[0-9]/', $contrasenna)) {
        $mensajeError .= 'La contraseña debe contener al menos un número. ';
        $esValida = false;
    }

    // 5. Validar al menos un carácter especial (puedes ajustar el grupo [!@#$%^&*()-_=+{};:,<.>/?] según necesites)
    if (!preg_match('/[!@#$%^&*()\-=_+{};:,<.>\/?]/', $contrasenna)) {
        $mensajeError .= 'La contraseña debe contener al menos un carácter especial (ej: !@#$%). ';
        $esValida = false;
    }

    return ['valido' => $esValida, 'mensaje' => trim($mensajeError)];
}

/**
 * Valida si la fecha de nacimiento corresponde a una edad mayor a la mínima permitida.
 *
 * @param string $fechaNacimiento La fecha de nacimiento en formato 'YYYY-MM-DD'.
 * @param int $edadMinima La edad mínima requerida.
 * @return array Arreglo asociativo ['valido' => bool, 'mensaje' => string]
 */
function validarEdad(string $fechaNacimiento, int $edadMinima): array {
    try {
        $fechaNacObj = new DateTime($fechaNacimiento);
        $fechaHoy = new DateTime();
        $diferencia = $fechaHoy->diff($fechaNacObj);
        $edad = $diferencia->y;

        if ($edad >= $edadMinima) {
            return ['valido' => true, 'mensaje' => 'Edad válida.'];
        } else {
            return ['valido' => false, 'mensaje' => "Debes tener al menos {$edadMinima} años."];
        }
    } catch (Exception $e) {
        // Manejar error si la fecha no es válida
        return ['valido' => false, 'mensaje' => 'Formato de fecha de nacimiento inválido. Usa YYYY-MM-DD.'];
    }
}


?>