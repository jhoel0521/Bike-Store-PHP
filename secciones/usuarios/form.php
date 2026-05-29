<?php
$txtID = $txtID ?? '';
$user = $user ?? '';
$password = $password ?? '';
$email = $email ?? '';
$role = $role ?? 'empleado';
$accion = $accion ?? 'Guardar';
?>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <p class="text-secondary small mb-1">Formulario de usuarios</p>
                <h2 class="h4 mb-0"><?= htmlspecialchars($accion ?? 'Guardar', ENT_QUOTES, 'UTF-8') ?> usuario</h2>
            </div>
            <span class="badge bg-primary-subtle text-primary rounded-pill">Optima UI</span>
        </div>
    </div>
    <div class="card-body p-4 p-lg-5">
        <?php if (!empty($mensaje)) { ?>
            <div class="alert alert-warning border-0 rounded-3 mb-4" role="alert">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php } ?>

        <form action="" method="post" class="row g-4">
            <div class="col-md-6">
                <label for="user" class="form-label text-secondary fw-semibold">Usuario</label>
                <input
                    type="text"
                    class="form-control form-control-lg rounded-3"
                    name="user"
                    id="user"
                    value="<?= htmlspecialchars((string) $user, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nombre de usuario"
                    required>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label text-secondary fw-semibold">Email</label>
                <input
                    type="email"
                    class="form-control form-control-lg rounded-3"
                    name="email"
                    id="email"
                    value="<?= htmlspecialchars((string) $email, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="correo@ejemplo.com"
                    required>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label text-secondary fw-semibold">Contraseña</label>
                <input
                    type="text"
                    class="form-control form-control-lg rounded-3"
                    name="password"
                    id="password"
                    value="<?= htmlspecialchars((string) $password, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Dejar vacío para conservar la actual">
                <div class="form-text">En edición, si lo dejas vacío se mantiene la contraseña actual.</div>
            </div>

            <div class="col-md-6">
                <label for="role" class="form-label text-secondary fw-semibold">Rol</label>
                <select class="form-select form-select-lg rounded-3" name="role" id="role" required>
                    <option value="">Seleccione un rol</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    <option value="empleado" <?= $role === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                </select>
            </div>

            <?php if (!empty($txtID)) { ?>
                <input type="hidden" name="txtID" value="<?= htmlspecialchars((string) $txtID, ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>

            <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary px-4 rounded-pill"><?= htmlspecialchars($accion ?? 'Guardar', ENT_QUOTES, 'UTF-8') ?></button>
                <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Cancelar</a>
            </div>
        </form>
    </div>
</div>