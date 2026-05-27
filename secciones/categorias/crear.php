<?php require_once __DIR__ . '/../../bd.php';
if ($_POST) {
    //Validacion del nombre categoria
    $category_name = (isset($_POST['category_name']) ? $_POST['category_name'] : "");
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
<br>
<?php include("form.php"); ?>

<?php include("../../templates/footer.php"); ?>