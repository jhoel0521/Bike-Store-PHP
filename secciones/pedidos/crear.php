<?php
require_once __DIR__ . '/../../bd.php';

// Solo usuarios autenticados pueden acceder
if (!app_is_logged_in()) {
    redirigir(base_url() . 'login.php');
}

// Mostrar formulario de creación (sin lógica POST)
$pedido = null;
$items = [];

// Obtener clientes y productos para el formulario
$clientes = \DB::getTabla("SELECT customer_id, CONCAT(first_name, ' ', last_name) AS customer_name FROM customers ORDER BY customer_name");
$productos = \DB::getTabla('SELECT product_id, product_name, price FROM products ORDER BY product_name');

$form_action = 'guardar.php';

?>
<?php include("../../templates/header.php"); ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Crear pedido</h1>
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
