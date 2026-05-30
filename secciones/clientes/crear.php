<?php
require_once __DIR__ . '/../../bd.php';

if (!app_is_logged_in()) {
    redirigir(base_url() . 'login.php');
}

if (request()->method() === 'POST') {
    $first_name = trim(request()->get('first_name') ?? '');
    $last_name = trim(request()->get('last_name') ?? '');
    $email = trim(request()->get('email') ?? '');
    $phone = trim(request()->get('phone') ?? '');
    $city = trim(request()->get('city') ?? '');

    if ($first_name === '' || $last_name === '' || $email === '') {
        redirigir_con_mensaje(base_url() . 'secciones/clientes/crear.php', 'Nombre y email son requeridos.', 'error');
    }

    try {
        \DB::ejecutarConsulta('INSERT INTO customers (first_name, last_name, email, phone, city) VALUES (:fn, :ln, :email, :phone, :city)', [
            ':fn' => $first_name,
            ':ln' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':city' => $city
        ]);
        redirigir_con_mensaje(base_url() . 'secciones/clientes/', 'Cliente creado correctamente.', 'success');
    } catch (PDOException $ex) {
        error_log('clientes/crear.php POST error: ' . $ex->getMessage());
        redirigir_con_mensaje(base_url() . 'secciones/clientes/crear.php', 'Error al crear cliente.', 'error');
    }
}

include __DIR__ . '/../../templates/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Crear cliente</h1>
        <p class="text-secondary mb-0">Registro de un nuevo cliente.</p>
    </div>
    <div>
        <a class="btn btn-outline-secondary" href="index.php">Volver</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body">
        <?php include __DIR__ . '/form.php'; ?>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>