<?php
require_once __DIR__ . '/../../bd.php';

// POST -> crear ítem para pedido existente
if (request()->method() === 'POST') {
    if (!app_is_logged_in()) api_error('No autorizado', 401);

    $order_id = (int) request()->get('order_id');
    $product_id = (int) request()->get('product_id');
    $quantity = (int) request()->get('quantity');
    $price = (float) request()->get('price');
    $discount = (float) request()->get('discount');

    // Normalizar descuento: si el usuario envía 10, lo interpretamos como 10% -> 0.10
    if ($discount > 1) {
        $discount = $discount / 100.0;
    }

    if ($order_id <= 0 || $product_id <= 0 || $quantity <= 0) {
        api_error('Parámetros inválidos', 400);
    }

    try {
        \DB::ejecutarConsulta('INSERT INTO order_items (order_id, product_id, quantity, price, discount) VALUES (:order_id, :product_id, :quantity, :price, :discount)', [
            ':order_id' => $order_id,
            ':product_id' => $product_id,
            ':quantity' => $quantity,
            ':price' => $price,
            ':discount' => $discount
        ]);
        $id = \DB::conectar()->lastInsertId();
        api_success('Ítem añadido', ['order_items_id' => $id], 201);
    } catch (PDOException $ex) {
        error_log('pedidos/items.php POST error: ' . $ex->getMessage());
        api_error('Error al añadir ítem', 500, $ex->getMessage());
    }
}

// DELETE -> eliminar ítem
if (request()->method() === 'DELETE') {
    if (!app_is_logged_in()) api_error('No autorizado', 401);

    $txtID = request()->get('txtID');
    if ($txtID === null || (int)$txtID <= 0) api_error('txtID requerido', 400);

    $id = (int)$txtID;
    try {
        \DB::ejecutarConsulta('DELETE FROM order_items WHERE order_items_id=:id', [':id' => $id]);
        api_success('Ítem eliminado', null, 200);
    } catch (PDOException $ex) {
        error_log('pedidos/items.php DELETE error: ' . $ex->getMessage());
        api_error('Error al eliminar ítem', 500, $ex->getMessage());
    }
}

// Otros métodos no permitidos
api_error('Método no soportado', 405);
