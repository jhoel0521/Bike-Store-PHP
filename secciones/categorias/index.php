<?php
require_once __DIR__ . '/../../bd.php';
app_require_auth();
// Eliminar solo mediante método HTTP DELETE (petición fetch desde cliente)
if (request()->method() === 'DELETE') {
    $txtID = request()->get('txtID');

    if ($txtID === null || $txtID === '') {
        api_error('Parámetro txtID es requerido.', 400);
    }

    $id = (int) $txtID;
    if ($id <= 0) {
        api_error('txtID inválido.', 400);
    }

    $categoria = \DB::getRegistro("SELECT category_id FROM categories WHERE category_id = :id", [":id" => $id]);
    if (!$categoria) {
        api_error('Categoría no encontrada.', 404);
    }

    $productosAsociados = (int) \DB::getValor("SELECT COUNT(*) FROM products WHERE category_id = :id", [":id" => $id]);
    if ($productosAsociados > 0) {
        api_error('No se puede eliminar esta categoría porque tiene productos asociados.', 409);
    }

    try {
        \DB::ejecutarConsulta("DELETE FROM categories WHERE category_id=:id", [":id" => $id]);
        api_no_content();
    } catch (PDOException $ex) {
        error_log('categorias/index.php DELETE error: ' . $ex->getMessage());
        api_error('Error interno al eliminar la categoría.', 500, $ex->getMessage());
    }
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
    <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 mb-0">Listado</h2>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tabla-categorias" class="table table-hover table-borderless align-middle mb-0">
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
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new DataTable('#tabla-categorias', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.3.1/i18n/es-ES.json'
            },
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
            pageLength: 10,
            order: [
                [0, 'asc']
            ],
        });
    });

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
                    Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                });
            }
        });
    }
</script>

<?php include("../../templates/footer.php"); ?>