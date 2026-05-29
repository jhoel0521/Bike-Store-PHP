<?php

/**
 * Devuelve el usuario autenticado actual o null si no existe sesión de acceso.
 *
 * @return array<string,mixed>|null
 */
function app_current_user(): ?array
{
    ensureSeccion();

    return $_SESSION['auth'] ?? null;
}

/**
 * Indica si hay una sesión de autenticación activa.
 *
 * @return bool
 */
function app_is_logged_in(): bool
{
    return app_current_user() !== null;
}

/**
 * Guarda los datos del usuario autenticado en la sesión.
 *
 * @param array<string,mixed> $registro
 * @return void
 */
function app_auth_login(array $registro): void
{
    ensureSeccion();
    session_regenerate_id(true);

    $_SESSION['auth'] = [
        'user_id' => (int)    ($registro['user_id'] ?? 0),
        'user'    => (string) ($registro['user']    ?? ''),
        'email'   => (string) ($registro['email']   ?? ''),
        'role'    => (string) ($registro['role']    ?? ''),
    ];

    $_SESSION['usuario'] = $_SESSION['auth']['user'];
    $_SESSION['role']    = $_SESSION['auth']['role'];
}

/**
 * Cierra la sesión del usuario actual.
 *
 * @return void
 */
function app_auth_logout(): void
{
    ensureSeccion();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

/**
 * Redirige al login si la sesión no está autenticada.
 *
 * @return void
 */
function app_require_auth(): void
{
    if (php_sapi_name() === 'cli' || app_is_public_script()) {
        return;
    }

    if (!app_is_logged_in()) {
        redirigir(base_url() . 'login.php');
    }
}

/**
 * Redirige al inicio si el usuario no tiene rol administrador.
 *
 * @return void
 */
function app_require_admin(): void
{
    app_require_auth();

    $usuario = app_current_user();

    if (($usuario['role'] ?? '') !== 'admin') {
        redirigir_con_mensaje(base_url() . 'index.php', 'No tienes permisos para acceder a esta sección.', 'error');
    }
}

/**
 * Convierte el rol técnico en una etiqueta legible.
 *
 * @param string $role
 * @return string
 */
function app_role_label(string $role): string
{
    return match ($role) {
        'admin'    => 'Administrador',
        'empleado' => 'Empleado',
        default    => ucfirst($role),
    };
}
