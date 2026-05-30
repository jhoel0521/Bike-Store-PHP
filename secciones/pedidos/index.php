<?php require_once __DIR__ . '/../../bd.php';

// Lista de pedidos
$lista_pedidos = \DB::getTabla("SELECT orders.*, 
(SELECT CONCAT(first_name, ' ', last_name) FROM customers WHERE customer_id=orders.customer_id LIMIT 1) as cliente,
(SELECT user FROM users WHERE user_id=orders.user_id LIMIT 1) as usuario,
(SELECT IFNULL(SUM(quantity*price*(1-IFNULL(discount,0))),0) FROM order_items WHERE order_id=orders.order_id) as total
FROM orders ORDER BY order_date DESC");

?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Ventas</span>
        <h1 class="h3 fw-bold mb-1">Pedidos</h1>
        <p class="text-secondary mb-0">Gestión de pedidos y sus líneas.</p>
    </div>
    <?php if (app_is_logged_in()) { ?>
        <a class="btn btn-primary px-4 rounded-pill" href="crear.php" role="button">
            <i class="bi bi-plus-circle me-2"></i>Nuevo pedido
        </a>
    <?php } ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 mb-0">Listado de pedidos</h2>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tabla-pedidos" class="table table-hover table-borderless align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <?php if (app_is_logged_in()) { ?><th class="text-end">Acciones</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_pedidos)) {
                        foreach ($lista_pedidos as $r) { ?>
                            <tr>
                                <td class="fw-semibold text-secondary"><?= $r['order_id'] ?></td>
                                <td><?= htmlspecialchars($r['cliente'] ?? 'Sin cliente', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($r['order_date'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($r['usuario'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="badge <?= ($r['estado'] === 'anulado') ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?> rounded-pill px-3 py-2">
                                        <?= htmlspecialchars($r['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="text-end fw-semibold text-success">$<?= number_format((float)$r['total'], 2) ?></td>
                                <?php if (app_is_logged_in()) { ?>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a class="btn btn-outline-secondary" href="crear.php?txtID=<?= $r['order_id'] ?>">Ver / Editar</a>
                                            <?php if (($r['estado'] ?? '') !== 'anulado') { ?>
                                                <a class="btn btn-outline-danger" href="javascript:anular(<?= $r['order_id'] ?>);">Anular</a>
                                            <?php } ?>
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
        new DataTable('#tabla-pedidos', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json'
            }
        });
    });

    function anular(id) {
        Swal.fire({
            title: '¿Anular este pedido?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('anular.php?txtID=' + encodeURIComponent(id), {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data && (data.success ?? false)) {
                            Swal.fire('Pedido anulado', '', 'success').then(() => location.reload());
                        } else Swal.fire('Error', data.message ?? 'Error', 'error');
                    }).catch(() => Swal.fire('Error', 'No se pudo anular', 'error'));
            }
        });
    }
</script>

<?php include("../../templates/footer.php"); ?>