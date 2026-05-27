<?php require_once __DIR__ . '/../../bd.php';
require_once __DIR__ . '/../../libs/functions.php';
 $txtID = '';
$category_name = '';

if (isset($_GET['txtId'])) {
    $txtID = (string) $_GET['txtId'];
    $registro = \DB::getRegistro("SELECT * FROM categories WHERE category_id=:id", [":id" => $txtID]);

    if (!$registro) {
        redirigir_con_mensaje('index.php', 'El registro solicitado no existe.', 'error');
    }

    $category_name = $registro["category_name"];
} else {
    redirigir_con_mensaje('index.php', 'Debes seleccionar una categoría válida.', 'warning');
}
//el codigo siguiente copio de crear.php
if ($_POST) {
    //Validacion del nombre categoria
    $txtID = (isset($_POST['txtID']) && $_POST['txtID'] !== '') ? $_POST['txtID'] : "";

    if ($txtID === '') {
        redirigir_con_mensaje('index.php', 'Debes seleccionar una categoría válida.', 'warning');
    }

    $category_name = (isset($_POST['category_name']) ? $_POST['category_name'] : "");
    //Preparar la insercion de datos
    \DB::ejecutarConsulta(
        "UPDATE categories SET category_name=:category_name WHERE category_id=:id",
        [":id" => $txtID, ":category_name" => $category_name]
    );
    redirigir_con_mensaje('index.php', 'Registro actualizado');
}
$accion = "Actualizar";
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Catálogo</span>
        <h1 class="h3 fw-bold mb-1">Editar categoría</h1>
        <p class="text-secondary mb-0">Ajusta el nombre sin perder el contexto del registro actual.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>
<?php include("../../templates/footer.php"); ?>