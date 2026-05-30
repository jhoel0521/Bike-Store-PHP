<?php
require_once __DIR__ . '/../../bd.php';

// Solo usuarios autenticados pueden editar
if (!app_is_logged_in()) {
    redirigir(base_url() . 'login.php');
}

// Si es POST, actualizar pedido
if (request()->method() === 'POST') {
    $txtID = request()->get('txtID');
    $id = $txtID ? (int)$txtID : 0;
    $customer_id = (int) request()->get('customer_id');
    $order_date = request()->get('order_date') ? str_replace('T', ' ', request()->get('order_date')) : null;

    if ($id <= 0) {
        redirigir_con_mensaje(base_url() . 'secciones/pedidos/', 'Pedido inválido.', 'error');
    }

    try {
        \DB::ejecutarConsulta('UPDATE orders SET customer_id=:customer_id, order_date=:order_date WHERE order_id=:id', [
            ':customer_id' => $customer_id,
            ':order_date' => $order_date,
            ':id' => $id
        ]);
        redirigir_con_mensaje(base_url() . 'secciones/pedidos/', 'Pedido actualizado.', 'success');
    } catch (PDOException $ex) {
        error_log('pedidos/editar.php POST error: ' . $ex->getMessage());
        redirigir_con_mensaje(base_url() . 'secciones/pedidos/editar.php?txtID=' . urlencode($id), 'Error al actualizar.', 'error');
    }
    exit;
}

// GET: mostrar pedido para edición (cliente/fecha)
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

$form_action = 'editar.php';

?>
<?php include("../../templates/header.php"); ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1"><?= $pedido ? 'Editar pedido #' . htmlspecialchars($pedido['order_id']) : 'Editar pedido' ?></h1>
        <p class="text-secondary mb-0">Modificar cliente y fecha del pedido. Los ítems se gestionan por separado.</p>
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
