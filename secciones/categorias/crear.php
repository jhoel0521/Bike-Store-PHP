<?php require_once __DIR__ . '/../../bd.php';
if (request()->method() === 'POST') {
    //Validacion del nombre categoria
    $category_name = request()->get('category_name', "");
    //Preparar la insercion de datos
    \DB::ejecutarConsulta(
        "INSERT INTO categories (category_id, category_name) VALUES (NULL, :category_name)",
        [":category_name" => $category_name]
    );
    redirigir_con_mensaje('index.php', 'Registro agregado');
}
$txtID = "";
$category_name = "";
$accion = "Agregar";
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Catálogo</span>
        <h1 class="h3 fw-bold mb-1">Nueva categoría</h1>
        <p class="text-secondary mb-0">Crea una entrada limpia y consistente para el catálogo.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>

<?php include("../../templates/footer.php"); ?>