<?php
require_once __DIR__ . '/../../bd.php';

// Solo usuarios autenticados pueden crear/editar
if (!app_is_logged_in()) {
    redirigir(base_url() . 'login.php');
}

$usuarioActual = app_current_user();

if (request()->method() === 'POST') {
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
        error_log('pedidos/crear.php POST error: ' . $ex->getMessage());
        redirigir_con_mensaje(base_url() . 'secciones/pedidos/crear.php', 'Error al crear el pedido.', 'error');
    }
}

// Si viene txtID mostrar pedido existente
$pedido = null;
$items = [];
if (($txtID = request()->get('txtID')) !== null) {
    $txtID = (int) $txtID;
    if ($txtID > 0) {
        $pedido = \DB::getRegistro('SELECT * FROM orders WHERE order_id=:id', [':id' => $txtID]);
        $items = \DB::getTabla('SELECT oi.*, p.product_name FROM order_items oi LEFT JOIN products p ON p.product_id=oi.product_id WHERE oi.order_id=:id', [':id' => $txtID]);
    }
}

// Obtener clientes y productos para el formulario
$clientes = \DB::getTabla("SELECT customer_id, CONCAT(first_name, ' ', last_name) AS customer_name FROM customers ORDER BY customer_name");
$productos = \DB::getTabla('SELECT product_id, product_name, price FROM products ORDER BY product_name');

?>
<?php include("../../templates/header.php"); ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1"><?= $pedido ? 'Pedido #' . htmlspecialchars($pedido['order_id']) : 'Crear pedido' ?></h1>
        <p class="text-secondary mb-0">Añadir líneas y guardar el pedido.</p>
    </div>
    <div>
        <a class="btn btn-outline-secondary me-2" href="index.php">Volver</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body">
        <?php include __DIR__ . '/form.php'; ?>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
