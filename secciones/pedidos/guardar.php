<?php
require_once __DIR__ . '/../../bd.php';

// Solo usuarios autenticados pueden crear
if (!app_is_logged_in()) {
    redirigir(base_url() . 'login.php');
}

$usuarioActual = app_current_user();

if (request()->method() !== 'POST') {
    redirigir(base_url() . 'secciones/pedidos/crear.php');
}

// Crear un pedido con ítems enviados como arrays
$customer_id = (int) request()->get('customer_id');
$order_date = request()->get('order_date') ? str_replace('T', ' ', request()->get('order_date')) : date('Y-m-d H:i:s');
$items_product = request()->get('product_id') ?: [];
$items_qty = request()->get('quantity') ?: [];
$items_price = request()->get('price') ?: [];
$items_discount = request()->get('discount') ?: [];

if ($customer_id <= 0) {
    redirigir_con_mensaje(base_url() . 'secciones/pedidos/crear.php', 'Cliente inválido.', 'error');
}

$pdo = \DB::conectar();
try {
    $pdo->beginTransaction();

    \DB::ejecutarConsulta("INSERT INTO orders (customer_id, order_date, user_id, estado) VALUES (:customer_id, :order_date, :user_id, :estado)", [
        ':customer_id' => $customer_id,
        ':order_date' => $order_date,
        ':user_id' => $usuarioActual['user_id'],
        ':estado' => 'activo'
    ]);

    $order_id = $pdo->lastInsertId();

    // Insertar ítems
    for ($i = 0; $i < count($items_product); $i++) {
        $pid = (int) ($items_product[$i] ?? 0);
        $qty = (int) ($items_qty[$i] ?? 0);
        $price = (float) ($items_price[$i] ?? 0);
        $discount = (float) ($items_discount[$i] ?? 0);
        if ($discount > 1) $discount = $discount / 100.0;

        if ($pid > 0 && $qty > 0) {
            \DB::ejecutarConsulta("INSERT INTO order_items (order_id, product_id, quantity, price, discount) VALUES (:order_id, :product_id, :quantity, :price, :discount)", [
                ':order_id' => $order_id,
                ':product_id' => $pid,
                ':quantity' => $qty,
                ':price' => $price,
                ':discount' => $discount
            ]);
        }
    }

    $pdo->commit();

    redirigir_con_mensaje(base_url() . 'secciones/pedidos/', 'Pedido creado correctamente.', 'success');
} catch (PDOException $ex) {
    $pdo->rollBack();
    error_log('pedidos/guardar.php POST error: ' . $ex->getMessage());
    redirigir_con_mensaje(base_url() . 'secciones/pedidos/crear.php', 'Error al crear el pedido.', 'error');
}
