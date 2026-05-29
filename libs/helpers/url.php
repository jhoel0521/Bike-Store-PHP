<?php

/**
 * Devuelve la URL base de la aplicación obtenida de la variable de
 * entorno `BASE_URL`. Si no existe, devuelve una URL por defecto
 * adecuada para entornos de desarrollo locales.
 *
 * Example:
 * echo base_url(); // -> "http://localhost:8080/" (según .env.example o configuración)
 *
 * @return string URL base terminada en '/'
 */
function base_url(): string
{
    $baseUrl = getenv('BASE_URL');
    $isHttps = false;

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isHttps = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $isHttps = strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
    } elseif (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
        $isHttps = true;
    }

    $scheme = $isHttps ? 'https' : 'http';

    if ($baseUrl === false || $baseUrl === '') {
        return $scheme . '://localhost/Bike-Store-PHP/';
    }

    $baseUrl = rtrim($baseUrl, '/') . '/';

    $parsedUrl = parse_url($baseUrl);

    if ($parsedUrl === false) {
        return $scheme . '://localhost/Bike-Store-PHP/';
    }

    if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] !== $scheme) {
        $baseUrl = preg_replace('/^https?:\/\//i', $scheme . '://', $baseUrl) ?? $baseUrl;
    }

    return $baseUrl;
}

/**
 * Redirige inmediatamente a la ruta indicada.
 *
 * Example:
 * redirigir('/login.php');
 *
 * @param string $ruta Ruta o URL destino
 * @return void
 */
function redirigir(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

/**
 * Devuelve la ruta del script actual dentro de la aplicación.
 *
 * @return string
 */
function app_current_script_path(): string
{
    return str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
}

/**
 * Indica si el script actual es público.
 *
 * @return bool
 */
function app_is_public_script(): bool
{
    if (php_sapi_name() === 'cli') {
        return true;
    }

    $appPath = rtrim((string) (parse_url(base_url(), PHP_URL_PATH) ?? ''), '/');
    $scriptPath = app_current_script_path();

    return in_array($scriptPath, [
        $appPath . '/login.php',
        $appPath . '/cerrar.php',
    ], true);
}
