<?php

/**
 * Establece un mensaje "flash" en la sesión para mostrarse tras una
 * redirección. Guarda el mensaje y su tipo (p. ej. 'success', 'error').
 *
 * Example:
 * flash_set('Guardado correctamente', 'success');
 *
 * @param string $mensaje Texto del mensaje a mostrar
 * @param string $tipo Tipo visual del mensaje (por ejemplo 'success'|'error'|'info')
 * @return void
 */
function flash_set(string $mensaje, string $tipo = 'success'): void
{
    ensureSeccion();
    $_SESSION['flash'] = [
        'mensaje' => $mensaje,
        'tipo'    => $tipo,
    ];
}

/**
 * Establece un mensaje flash y redirige a la ruta indicada.
 *
 * Example:
 * redirigir_con_mensaje('/productos/index.php', 'Producto creado', 'success');
 *
 * @param string $ruta Ruta o URL destino (relativa o absoluta)
 * @param string $mensaje Mensaje a mostrar tras la redirección
 * @param string $tipo Tipo de mensaje (ver `flash_set`)
 * @return void
 */
function redirigir_con_mensaje(string $ruta, string $mensaje, string $tipo = 'success'): void
{
    flash_set($mensaje, $tipo);
    redirigir($ruta);
}

/**
 * Extrae el mensaje flash (si existe) y devuelve el HTML/JS necesario
 * para mostrarlo mediante SweetAlert. El mensaje se elimina de la sesión
 * tras su lectura.
 *
 * Example:
 * echo flash_render();
 *
 * @return string HTML/JS del modal o cadena vacía si no hay flash
 */
function flash_render(): string
{
    $flash = null;

    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    }

    if ($flash === null) {
        return '';
    }

    $tipo    = json_encode($flash['tipo'],    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $mensaje = json_encode($flash['mensaje'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return <<<HTML
            <script>
                Swal.fire({
                    icon: {$tipo},
                    title: {$mensaje}
                });
            </script>
            HTML;
}
