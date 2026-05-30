<?php
require_once __DIR__ . '/../../bd.php';

if (!app_is_logged_in()) {
	redirigir(base_url() . 'login.php');
}

$pedido = null;
$id = (int) (request()->get('txtID') ?? 0);
if ($id <= 0) {
	redirigir_con_mensaje(base_url() . 'secciones/clientes/', 'Cliente no especificado.', 'error');
}

if (request()->method() === 'POST') {
	$first_name = trim(request()->get('first_name') ?? '');
	$last_name = trim(request()->get('last_name') ?? '');
	$email = trim(request()->get('email') ?? '');
	$phone = trim(request()->get('phone') ?? '');
	$city = trim(request()->get('city') ?? '');

	if ($first_name === '' || $last_name === '' || $email === '') {
		redirigir_con_mensaje(base_url() . 'secciones/clientes/editar.php?txtID=' . $id, 'Nombre y email son requeridos.', 'error');
	}

	try {
		\DB::ejecutarConsulta('UPDATE customers SET first_name=:fn, last_name=:ln, email=:email, phone=:phone, city=:city WHERE customer_id=:id', [
			':fn' => $first_name,
			':ln' => $last_name,
			':email' => $email,
			':phone' => $phone,
			':city' => $city,
			':id' => $id
		]);
		redirigir_con_mensaje(base_url() . 'secciones/clientes/', 'Cliente actualizado.', 'success');
	} catch (PDOException $ex) {
		error_log('clientes/editar.php POST error: ' . $ex->getMessage());
		redirigir_con_mensaje(base_url() . 'secciones/clientes/editar.php?txtID=' . $id, 'Error al actualizar cliente.', 'error');
	}
}

$cliente = \DB::getRegistro('SELECT * FROM customers WHERE customer_id=:id', [':id' => $id]);
if (!$cliente) {
	redirigir_con_mensaje(base_url() . 'secciones/clientes/', 'Cliente no encontrado.', 'error');
}

include __DIR__ . '/../../templates/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
	<div>
		<h1 class="h3 fw-bold mb-1">Editar cliente #<?= htmlspecialchars($cliente['customer_id']) ?></h1>
		<p class="text-secondary mb-0">Modifica los datos del cliente.</p>
	</div>
	<div>
		<a class="btn btn-outline-secondary" href="index.php">Volver</a>
	</div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
	<div class="card-body">
		<?php $clienteVar = $cliente; $cliente = $clienteVar; include __DIR__ . '/form.php'; ?>
	</div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>

