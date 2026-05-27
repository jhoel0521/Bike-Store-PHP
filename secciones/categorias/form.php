<?php
$txtID = $txtID ?? '';
$category_name = $category_name ?? '';
$accion = $accion ?? 'Guardar';
?>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <p class="text-secondary small mb-1">Formulario de categorías</p>
                <h2 class="h4 mb-0"><?= htmlspecialchars($accion ?? 'Guardar', ENT_QUOTES, 'UTF-8') ?> categoría</h2>
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
            <div class="col-12">
                <label for="category_name" class="form-label text-secondary fw-semibold">Nombre de la categoría</label>
                <input
                    type="text"
                    class="form-control form-control-lg rounded-3"
                    name="category_name"
                    id="category_name"
                    value="<?= htmlspecialchars($category_name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Describa la categoría"
                    required
                />
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