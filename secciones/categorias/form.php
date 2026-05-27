<?php
$txtID = $txtID ?? '';
$category_name = $category_name ?? '';
$accion = $accion ?? 'Guardar';
?>
<div class="card">
    <div class="card-header">Informaci&oacute;n de categorias</div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="category_name" class="form-label">Nombre de la categoria</label>
                <input
                    type="text"
                    class="form-control"
                    name="category_name"
                    id="category_name"
                    value="<?= htmlspecialchars($category_name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Describa la categoria"
                    required
                />
            </div>

            <?php if (!empty($txtID)) { ?>
                <input type="hidden" name="txtID" value="<?= htmlspecialchars((string) $txtID, ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>

            <button type="submit" class="btn btn-success"><?= $accion ?? 'Guardar' ?></button>
            <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>