<?php require_once __DIR__ . '/../../bd.php';
app_require_auth();
require_once __DIR__ . '/../../libs/functions.php';
$txtID = '';
$category_name = '';


//el codigo siguiente copio de crear.php
if (request()->method() === 'POST') {
    //Validacion del nombre categoria
    $txtID = request()->get('txtID', "");

    if ($txtID === '') {
        redirigir_con_mensaje('index.php', 'Debes seleccionar una categoría válida.', 'warning');
    }

    $category_name = request()->get('category_name', "");
    //Preparar la insercion de datos
    \DB::ejecutarConsulta(
        "UPDATE categories SET category_name=:category_name WHERE category_id=:id",
        [":id" => $txtID, ":category_name" => $category_name]
    );
    redirigir_con_mensaje('index.php', 'Registro actualizado');
} else {
    $txtID = (string) request()->get('txtID', '');

    if ($txtID !== '') {
        $registro = \DB::getRegistro("SELECT * FROM categories WHERE category_id=:id", [":id" => $txtID]);

        if (!$registro) {
            redirigir_con_mensaje('index.php', 'El registro solicitado no existe.', 'error');
        }

        $category_name = $registro["category_name"];
    } else {
        redirigir_con_mensaje('index.php', 'Debes seleccionar una categoría válida.', 'warning');
    }
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