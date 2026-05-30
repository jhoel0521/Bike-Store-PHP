<?php
require_once __DIR__ . '/../../bd.php';

// Eliminar cliente mediante método DELETE
if (request()->method() === 'DELETE') {
    if (!app_is_logged_in()) {
        api_error('No autorizado', 401);
    }

    $txtID = request()->get('txtID');
    if ($txtID === null || (int)$txtID <= 0) api_error('txtID requerido', 400);

    $id = (int)$txtID;
    try {
        \DB::ejecutarConsulta('DELETE FROM customers WHERE customer_id=:id', [':id' => $id]);
        api_no_content();
    } catch (PDOException $ex) {
        error_log('clientes/index.php DELETE error: ' . $ex->getMessage());
        // FK violation
        $errorInfo = $ex->errorInfo ?? null;
        $sqlState = is_array($errorInfo) ? ($errorInfo[0] ?? '') : '';
        $driverCode = is_array($errorInfo) ? ($errorInfo[1] ?? 0) : 0;
        if ($sqlState === '23000' && (int)$driverCode === 1451) {
            api_error('No se puede eliminar el cliente porque tiene registros relacionados.', 409, $ex->getMessage());
        }
        api_error('Error al eliminar cliente', 500, $ex->getMessage());
    }
}

// Listado de clientes
$clientes = \DB::getTabla("SELECT customer_id, CONCAT(first_name, ' ', last_name) AS nombre, email, phone, city FROM customers ORDER BY customer_id DESC");

include __DIR__ . '/../../templates/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Clientes</span>
        <h1 class="h3 fw-bold mb-1">Clientes</h1>
        <p class="text-secondary mb-0">Gestión de clientes registrados.</p>
    </div>
    <?php if (app_is_logged_in()) { ?>
        <a class="btn btn-primary px-4 rounded-pill" href="crear.php" role="button">
            <i class="bi bi-plus-circle me-2"></i>Nuevo cliente
        </a>
    <?php } ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 mb-0">Listado</h2>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tabla-clientes" class="table table-hover table-borderless align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <?php if (app_is_logged_in()) { ?><th class="text-end">Acciones</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clientes)) {
                        foreach ($clientes as $c) { ?>
                            <tr>
                                <td class="fw-semibold text-secondary"><?= $c['customer_id'] ?></td>
                                <td><?= htmlspecialchars($c['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($c['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($c['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($c['city'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <?php if (app_is_logged_in()) { ?>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a class="btn btn-outline-secondary" href="editar.php?txtID=<?= $c['customer_id'] ?>">Editar</a>
                                            <a class="btn btn-outline-danger" href="javascript:borrar(<?= $c['customer_id'] ?>);">Eliminar</a>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new DataTable('#tabla-clientes', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json'
            }
        });
    });

    function borrar(id) {
        Swal.fire({
            title: '¿Deseas eliminar este cliente?',
            'icon': 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('index.php?txtID=' + encodeURIComponent(id), {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.status === 204) {
                            Swal.fire('Registro eliminado', '', 'success').then(() => location.reload());
                            return;
                        }
                        return response.json().then(data => {
                            if (data && (data.success ?? false)) Swal.fire(data.message || 'Ok', '', 'success').then(() => location.reload());
                            else Swal.fire(data.message || 'Error', '', 'error');
                        });
                    }).catch(() => Swal.fire('Error', 'No se pudo eliminar', 'error'));
            }
        });
    }
</script>

<?php include("../../templates/footer.php"); ?>