<?php

/**
 * Inicia la sesión PHP si no está ya activa.
 *
 * Garantiza que `$_SESSION` esté disponible antes de usarlo.
 *
 * Example:
 * ensureSeccion();
 *
 * @return void
 */
function ensureSeccion(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}
