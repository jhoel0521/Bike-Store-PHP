<?php require_once __DIR__ . '/../../bd.php';

// Eliminar solo mediante método HTTP DELETE (petición fetch desde cliente)
if (request()->method() === 'DELETE') {
    if (!app_is_logged_in()) {
        api_error('No autorizado.', 401);
    }

    $txtID = request()->get('txtID');
    if ($txtID === null || $txtID === '') {
        api_error('Parámetro txtID es requerido.', 400);
    }

    $id = (int) $txtID;
    if ($id <= 0) {
        api_error('txtID inválido.', 400);
    }

    $registro_recuperado = \DB::getRegistro("SELECT imagen FROM products WHERE product_id=:id", [":id" => $id]);
    if (!$registro_recuperado) {
        api_error('Producto no encontrado.', 404);
    }

    try {
        \DB::ejecutarConsulta("DELETE FROM products WHERE product_id=:id", [":id" => $id]);

        // Borrar archivo solo después de eliminar el registro en la BD
        if (isset($registro_recuperado["imagen"]) && $registro_recuperado["imagen"] != "") {
            $path = __DIR__ . "/img/" . $registro_recuperado["imagen"];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        api_no_content();
    } catch (PDOException $ex) {
        error_log('products/index.php DELETE error: ' . $ex->getMessage());

        // Detectar violación de FK (MySQL error 1451) y responder 409 Conflict
        $errorInfo = $ex->errorInfo ?? null;
        $sqlState = is_array($errorInfo) ? ($errorInfo[0] ?? '') : '';
        $driverCode = is_array($errorInfo) ? ($errorInfo[1] ?? 0) : 0;

        if ($sqlState === '23000' && (int)$driverCode === 1451) {
            api_error('No se puede eliminar el producto porque tiene registros relacionados (pedidos).', 409, $ex->getMessage());
        }

        api_error('Error interno al eliminar el producto.', 500, $ex->getMessage());
    }
}
//Consulta de productos y categorias para visualizar como unico registro
$lista_productos = \DB::getTabla("SELECT *,
(SELECT category_name FROM categories WHERE category_id=products.category_id limit 1) as categoria
FROM products ORDER BY product_id DESC");
//print_r($lista_categorias);
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Inventario</span>
        <h1 class="h3 fw-bold mb-1">Productos</h1>
        <p class="text-secondary mb-0">Control visual del inventario con acciones claras.</p>
    </div>
    <?php if (app_is_logged_in()) { ?>
        <a class="btn btn-primary px-4 rounded-pill" href="crear.php" role="button">
            <i class="bi bi-plus-circle me-2"></i>Nuevo producto
        </a>
    <?php } ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 mb-0">Listado</h2>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tabla-productos" class="table table-hover table-borderless align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">ID</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Nombre</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Imagen</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Año Modelo</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Precio</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Categoría</th>
                        <?php if (app_is_logged_in()) { ?>
                            <th scope="col" class="text-secondary fw-semibold small text-uppercase text-end">Acciones</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_productos)) { ?>
                        <?php foreach ($lista_productos as $registro) { ?>
                            <tr>
                                <td class="fw-semibold text-secondary"><?php echo $registro['product_id']; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($registro['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($registro['imagen'])) { ?>
                                            <img
                                                src="img/<?php echo htmlspecialchars($registro['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                                                class="rounded-3 shadow-sm app-table-thumb"
                                                alt="Imagen del producto" />
                                        <?php } else { ?>
                                            <div class="rounded-3 bg-light text-secondary d-inline-flex align-items-center justify-content-center app-table-thumb">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($registro['model_year'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="fw-semibold text-success">$<?php echo number_format((float) $registro['price'], 2); ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        <?php echo htmlspecialchars($registro['categoria'] ?? 'Sin categoría', ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <?php if (app_is_logged_in()) { ?>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de producto">
                                            <a class="btn btn-outline-secondary" href="editar.php?txtID=<?php echo $registro['product_id']; ?>" role="button">
                                                <i class="bi bi-pencil me-1"></i>Editar
                                            </a>
                                            <a class="btn btn-outline-danger" href="javascript:borrar(<?php echo $registro['product_id']; ?>);" role="button">
                                                <i class="bi bi-trash3 me-1"></i>Eliminar
                                            </a>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new DataTable('#tabla-productos', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json'
            },
            columnDefs: [{
                orderable: false,
                searchable: false,
                targets: [2, -1]
            }],
            pageLength: 10,
            order: [
                [0, 'asc']
            ],
        });
    });

    function borrar(id) {
        Swal.fire({
            title: '¿Deseas eliminar este producto?',
            icon: 'warning',
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
                }).then(response => {
                    if (response.status === 204) {
                        Swal.fire({ title: 'Registro eliminado', icon: 'success' }).then(() => location.reload());
                        return;
                    }

                    return response.json().then(data => {
                        if (response.ok && (data.success ?? true)) {
                            Swal.fire({ title: data.message ?? 'Registro eliminado', icon: 'success' }).then(() => location.reload());
                        } else {
                            Swal.fire({ title: data.message ?? 'Error interno', icon: 'error' });
                        }
                    });
                }).catch(() => {
                    Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
                });
            }
        });
    }
</script>
<?php include("../../templates/footer.php"); ?>