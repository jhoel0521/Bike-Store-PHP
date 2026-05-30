<?php
// Variables esperadas: $cliente (array|null)
$cliente = $cliente ?? null;
?>
<form method="post" action="<?= $cliente ? 'editar.php?txtID=' . (int)$cliente['customer_id'] : 'crear.php' ?>">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($cliente['first_name'] ?? '') ?>" />
        </div>
        <div class="col-md-6">
            <label class="form-label">Apellido</label>
            <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($cliente['last_name'] ?? '') ?>" />
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" />
        </div>
        <div class="col-md-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($cliente['phone'] ?? '') ?>" />
        </div>
        <div class="col-md-3">
            <label class="form-label">Ciudad</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($cliente['city'] ?? '') ?>" />
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><?= $cliente ? 'Guardar cambios' : 'Guardar' ?></button>
    </div>
</form>