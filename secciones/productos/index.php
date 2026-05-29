<?php require_once __DIR__ . '/../../bd.php';

//Envio de parametros en la URLo en el metodo GET
if (isset($_GET["txtID"])) {
    if (!app_is_logged_in()) {
        redirigir(base_url() . 'login.php');
    }
    $txtID = (isset($_GET["txtID"])) ? $_GET['txtID'] : "";
    //Buscar el archivo relacionadocon el producto
    $registro_recuperado = \DB::getRegistro("SELECT imagen FROM products WHERE product_id=:id", [":id" => $txtID]);
    //Buscar Archivo foto y borralo
    if (isset($registro_recuperado["imagen"]) && $registro_recuperado["imagen"] != "") {
        if (file_exists("./img/" . $registro_recuperado["imagen"])) {
            unlink("./img/" . $registro_recuperado["imagen"]);
        }
    }
    //Borra los datos del producto
    \DB::ejecutarConsulta("DELETE FROM products WHERE product_id=:id", [":id" => $txtID]);
    $mensaje = "Registro eliminado";
    redirigir_con_mensaje('index.php', $mensaje);
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
    <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
        <div>
            <h2 class="h5 mb-0">Listado</h2>
            <p class="text-secondary small mb-0">Total: <?= count($lista_productos) ?></p>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0">
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
                    <?php } else { ?>
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-5 text-secondary app-empty-state">
                                    <i class="bi bi-bicycle display-6 d-block mb-2 text-primary"></i>
                                    <p class="mb-0">Todavía no hay productos registrados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>