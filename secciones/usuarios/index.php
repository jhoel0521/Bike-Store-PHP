<?php require_once __DIR__ . '/../../bd.php';
app_require_admin();

if (isset($_GET['txtID'])) {
    $txtID = (int) $_GET['txtID'];
    $usuarioActual = app_current_user();

    if ($usuarioActual !== null && (int) ($usuarioActual['user_id'] ?? 0) === $txtID) {
        redirigir_con_mensaje('index.php', 'No puedes eliminar tu propio usuario activo.', 'warning');
    }

    $registro = \DB::getRegistro("SELECT user_id, role FROM users WHERE user_id = :id", [":id" => $txtID]);

    if (!$registro) {
        redirigir_con_mensaje('index.php', 'El usuario solicitado no existe.', 'error');
    }

    if (($registro['role'] ?? '') === 'admin') {
        $totalAdmins = (int) \DB::getValor("SELECT COUNT(*) FROM users WHERE role = 'admin'");

        if ($totalAdmins <= 1) {
            redirigir_con_mensaje('index.php', 'Debe existir al menos un administrador.', 'warning');
        }
    }

    \DB::ejecutarConsulta("DELETE FROM users WHERE user_id = :id", [":id" => $txtID]);
    redirigir_con_mensaje('index.php', 'Registro eliminado');
}

$lista_usuarios = \DB::getTabla("SELECT user_id, user, email, role FROM users ORDER BY user_id DESC");
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Seguridad</span>
        <h1 class="h3 fw-bold mb-1">Usuarios</h1>
        <p class="text-secondary mb-0">Administración de accesos, correos y roles.</p>
    </div>
    <a class="btn btn-primary px-4 rounded-pill" href="crear.php" role="button">
        <i class="bi bi-person-plus me-2"></i>Nuevo usuario
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="h5 mb-0">Listado</h2>
            <p class="text-secondary small mb-0">Total: <?= count($lista_usuarios) ?></p>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">ID</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Usuario</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Email</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Rol</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_usuarios)) { ?>
                        <?php foreach ($lista_usuarios as $registro) { ?>
                            <tr>
                                <td class="fw-semibold text-secondary"><?php echo $registro['user_id']; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($registro['user'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($registro['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        <?php echo htmlspecialchars(app_role_label((string) $registro['role']), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de usuario">
                                        <a class="btn btn-outline-secondary" href="editar.php?txtID=<?php echo $registro['user_id']; ?>" role="button">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
                                        <a class="btn btn-outline-danger" href="javascript:borrar(<?php echo $registro['user_id']; ?>);" role="button">
                                            <i class="bi bi-trash3 me-1"></i>Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5 text-secondary app-empty-state">
                                    <i class="bi bi-person-gear display-6 d-block mb-2 text-primary"></i>
                                    <p class="mb-0">Todavía no hay usuarios registrados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function borrar(id) {
        Swal.fire({
            title: '¿Deseas eliminar este usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?txtID=' + id;
            }
        });
    }
</script>

<?php include("../../templates/footer.php"); ?><?php include("../../templates/header.php"); ?>

<?php include("../../templates/footer.php"); ?>
