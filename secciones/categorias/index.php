<?php
require_once __DIR__ . '/../../bd.php';
app_require_auth();
//Envio de parametros en la URL o en el metodo GET
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    $productosAsociados = \DB::getValor("SELECT COUNT(*) FROM products WHERE category_id = :id", [":id" => $txtID]);

    if ($productosAsociados > 0) {
        $mensaje = "No se puede eliminar esta categoría porque tiene productos asociados.";
    } else {
        try {
            \DB::ejecutarConsulta("DELETE FROM categories WHERE category_id=:id", [":id" => $txtID]);
            $mensaje = "Registro eliminado";
        } catch (PDOException $ex) {
            $mensaje = "Error al eliminar la categoría.";
        }
    }

    redirigir_con_mensaje('index.php', $mensaje);
}

//Consulta de categorias
$lista_categorias = \DB::getTabla("SELECT * FROM categories ORDER BY category_id ASC");
//print_r($lista_categorias);
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Catálogo</span>
        <h1 class="h3 fw-bold mb-1">Categorías</h1>
        <p class="text-secondary mb-0">Organiza el catálogo con una vista clara y ligera.</p>
    </div>
    <a class="btn btn-primary px-4 rounded-pill" href="crear.php" role="button">
        <i class="bi bi-plus-circle me-2"></i>Nueva categoría
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="h5 mb-0">Listado</h2>
            <p class="text-secondary small mb-0">Total: <?= count($lista_categorias) ?></p>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">ID</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase">Nombre</th>
                        <th scope="col" class="text-secondary fw-semibold small text-uppercase text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_categorias)) { ?>
                        <?php foreach ($lista_categorias as $registro) { ?>
                            <tr>
                                <td class="fw-semibold text-secondary"><?php echo $registro['category_id']; ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        <?php echo htmlspecialchars($registro['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Acciones de categoría">
                                        <a class="btn btn-outline-secondary" href="editar.php?txtId=<?php echo $registro['category_id']; ?>" role="button">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
                                        <a class="btn btn-outline-danger" href="#" role="button" onclick="borrar('<?php echo $registro['category_id']; ?>')">
                                            <i class="bi bi-trash3 me-1"></i>Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3">
                                <div class="text-center py-5 text-secondary app-empty-state">
                                    <i class="bi bi-tags display-6 d-block mb-2 text-primary"></i>
                                    <p class="mb-0">Todavía no hay categorías registradas.</p>
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
            title: '¿Deseas eliminar esta categoría?',
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

<?php include("../../templates/footer.php"); ?>