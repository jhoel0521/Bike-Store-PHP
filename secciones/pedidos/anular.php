<?php
require_once __DIR__ . '/../../bd.php';

if (request()->method() !== 'DELETE') {
    api_error('Método no permitido', 405);
}

if (!app_is_logged_in()) {
    api_error('No autorizado', 401);
}

$txtID = request()->get('txtID');
if ($txtID === null || (int)$txtID <= 0) {
    api_error('Parámetro txtID requerido', 400);
}

$id = (int)$txtID;
$pedido = \DB::getRegistro('SELECT * FROM orders WHERE order_id=:id', [':id' => $id]);
if (!$pedido) {
    api_error('Pedido no encontrado', 404);
}

try {
    \DB::ejecutarConsulta('UPDATE orders SET estado=:estado WHERE order_id=:id', [':estado' => 'anulado', ':id' => $id]);
    api_success('Pedido anulado', null, 200);
} catch (PDOException $ex) {
    error_log('pedidos/anular.php DELETE error: ' . $ex->getMessage());
    api_error('Error interno al anular', 500, $ex->getMessage());
}
