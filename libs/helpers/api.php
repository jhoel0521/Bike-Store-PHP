<?php

/**
 * Helpers para respuestas API JSON.
 * Provee funciones auxiliares para enviar respuestas con códigos HTTP
 * y payload consistente.
 */

function api_response($data = null, int $status = 200, array $headers = []): void
{
    if (!headers_sent()) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        foreach ($headers as $k => $v) {
            header(sprintf('%s: %s', $k, $v));
        }
    }

    if ($status === 204) {
        // 204 No Content -> no body
        exit;
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        error_log('api_response: json_encode failed: ' . json_last_error_msg());
        // Fallback: enviar mensaje simple
        echo json_encode(['success' => false, 'message' => 'Error serializando respuesta.'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        echo $json;
    }
    exit;
}

function api_success(string $message = 'OK', $data = null, int $status = 200): void
{
    $payload = ['success' => true, 'message' => $message];
    if ($data !== null) {
        $payload['data'] = $data;
    }
    api_response($payload, $status);
}

function api_error(string $message = 'Error', int $status = 400, $details = null): void
{
    $payload = ['success' => false, 'message' => $message];

    // Log details server-side always for debugging
    if ($details !== null) {
        error_log('API error: ' . $details);
    }

    // Mostrar detalles en la respuesta solo si APP_DEBUG está activado o display_errors está on
    $debug = false;
    $envDebug = getenv('APP_DEBUG');
    if ($envDebug !== false) {
        $envDebug = strtolower($envDebug);
        $debug = ($envDebug === '1' || $envDebug === 'true' || $envDebug === 'on');
    }
    if (!$debug && ini_get('display_errors')) {
        $debug = true;
    }

    if ($details !== null && $debug) {
        $payload['details'] = $details;
    }

    api_response($payload, $status);
}

function api_no_content(): void
{
    api_response(null, 204);
}
