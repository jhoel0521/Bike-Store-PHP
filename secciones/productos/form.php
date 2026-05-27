<?php
$txtID = $txtID ?? '';
$product_name = $product_name ?? '';
$foto_actual = $foto_actual ?? '';
$model_year = $model_year ?? '';
$price = $price ?? '';
$category_id = $category_id ?? '';
$lista_categorias = $lista_categorias ?? [];
$accion = $accion ?? 'Guardar';
?>
<div class="card">
    <div class="card-header">Datos del producto</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Descripci&oacute;n</label>
                <input
                    type="text"
                    class="form-control"
                    name="product_name"
                    id="product_name"
                    value="<?= htmlspecialchars($product_name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nombre del producto"
                    required>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Imagen del producto</label>
                <input
                    type="file"
                    class="form-control"
                    name="foto"
                    id="foto"
                    accept="image/*"
                    <?= empty($foto_actual) ? 'required' : '' ?>>
                <div class="form-text">Sube una imagen en formato jpg, png o webp.</div>
            </div>

            <div class="mb-3">
                <img
                    id="preview"
                    src="<?= !empty($foto_actual) ? 'img/' . htmlspecialchars($foto_actual, ENT_QUOTES, 'UTF-8') : '#' ?>"
                    alt="Vista previa"
                    class="img-thumbnail"
                    style="max-width: 220px; <?= empty($foto_actual) ? 'display:none;' : '' ?>">
            </div>

            <div class="mb-3">
                <label for="model_year" class="form-label">A&ntilde;o del modelo</label>
                <input
                    type="number"
                    class="form-control"
                    name="model_year"
                    id="model_year"
                    value="<?= htmlspecialchars((string) ($model_year ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="A&ntilde;o del producto"
                    required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    name="price"
                    id="price"
                    value="<?= htmlspecialchars((string) ($price ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Precio"
                    required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Categor&iacute;a</label>
                <select class="form-select" name="category_id" id="category_id" required>
                    <option value="">Seleccione una categor&iacute;a</option>
                    <?php foreach ($lista_categorias as $registro) { ?>
                        <option value="<?= $registro['category_id'] ?>" <?= (($category_id ?? '') == $registro['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($registro['category_name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <?php if (!empty($txtID)) { ?>
                <input type="hidden" name="txtID" value="<?= htmlspecialchars((string) $txtID, ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>

            <button type="submit" class="btn btn-outline-success"><?= $accion ?? 'Guardar' ?></button>
            <a class="btn btn-outline-secondary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<script>
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');

    if (fotoInput && preview) {
        fotoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            const imageUrl = URL.createObjectURL(file);
            preview.src = imageUrl;
            preview.style.display = 'block';
        });
    }
</script>