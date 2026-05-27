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
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <p class="text-secondary small mb-1">Formulario de productos</p>
                <h2 class="h4 mb-0"><?= htmlspecialchars($accion ?? 'Guardar', ENT_QUOTES, 'UTF-8') ?> producto</h2>
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

        <form action="" method="post" enctype="multipart/form-data" class="row g-4">
            <div class="col-12">
                <label for="product_name" class="form-label text-secondary fw-semibold">Descripción</label>
                <input
                    type="text"
                    class="form-control form-control-lg rounded-3"
                    name="product_name"
                    id="product_name"
                    value="<?= htmlspecialchars($product_name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nombre del producto"
                    required>
            </div>

            <div class="col-md-6">
                <label for="foto" class="form-label text-secondary fw-semibold">Imagen del producto</label>
                <input
                    type="file"
                    class="form-control rounded-3"
                    name="foto"
                    id="foto"
                    accept="image/*"
                    <?= empty($foto_actual) ? 'required' : '' ?>>
                <div class="form-text">Sube una imagen en formato jpg, png o webp.</div>
            </div>

            <div class="col-md-6">
                <label class="form-label text-secondary fw-semibold">Vista previa</label>
                <div class="app-soft-panel rounded-3 p-3 text-center">
                    <img
                        id="preview"
                        src="<?= !empty($foto_actual) ? 'img/' . htmlspecialchars($foto_actual, ENT_QUOTES, 'UTF-8') : '' ?>"
                        alt="Vista previa"
                        class="img-fluid rounded-3 shadow-sm <?= empty($foto_actual) ? 'd-none' : '' ?> app-product-preview">
                    <div id="previewPlaceholder" class="<?= empty($foto_actual) ? '' : 'd-none' ?> text-secondary small py-5">
                        La vista previa aparecerá aquí.
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <label for="model_year" class="form-label text-secondary fw-semibold">Año del modelo</label>
                <input
                    type="number"
                    class="form-control rounded-3"
                    name="model_year"
                    id="model_year"
                    value="<?= htmlspecialchars((string) ($model_year ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Año del producto"
                    required>
            </div>

            <div class="col-md-4">
                <label for="price" class="form-label text-secondary fw-semibold">Precio</label>
                <input
                    type="number"
                    step="0.01"
                    class="form-control rounded-3"
                    name="price"
                    id="price"
                    value="<?= htmlspecialchars((string) ($price ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Precio"
                    required>
            </div>

            <div class="col-md-4">
                <label for="category_id" class="form-label text-secondary fw-semibold">Categoría</label>
                <select class="form-select rounded-3" name="category_id" id="category_id" required>
                    <option value="">Seleccione una categoría</option>
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

            <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary px-4 rounded-pill"><?= htmlspecialchars($accion ?? 'Guardar', ENT_QUOTES, 'UTF-8') ?></button>
                <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    if (fotoInput && preview && previewPlaceholder) {
        fotoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];

            if (!file) {
                preview.classList.add('d-none');
                previewPlaceholder.classList.remove('d-none');
                return;
            }

            const imageUrl = URL.createObjectURL(file);
            preview.src = imageUrl;
            preview.classList.remove('d-none');
            previewPlaceholder.classList.add('d-none');
        });
    }
</script>