<?php

function base_url(): string
{
    $baseUrl = getenv('BASE_URL');

    if ($baseUrl === false || $baseUrl === '') {
        return 'http://localhost/Bike-Store-PHP/';
    }

    return rtrim($baseUrl, '/') . '/';
}

function ensureSeccion(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function iniciar_sesion(): void
{
    ensureSeccion();
}

function dd(mixed $valor): void
{
    echo '<pre>';
    var_dump($valor);
    echo '</pre>';
    exit;
}

function flash_set(string $mensaje, string $tipo = 'success'): void
{
    ensureSeccion();
    $_SESSION['flash'] = [
        'mensaje' => $mensaje,
        'tipo' => $tipo,
    ];
}

function redirigir_con_mensaje(string $ruta, string $mensaje, string $tipo = 'success'): void
{
    flash_set($mensaje, $tipo);
    header('Location: ' . $ruta);
    exit;
}

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

    $tipo = json_encode($flash['tipo'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
