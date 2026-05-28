<?php require_once __DIR__ . '/../../bd.php';
require_once __DIR__ . '/../../libs/functions.php';
$txtID = '';
$product_name = '';
$foto_actual = '';
$model_year = '';
$price = '';
$category_id = '';

if (isset($_GET["txtID"])) {
    $txtID = (string) $_GET['txtID'];
    $registro = \DB::getRegistro("SELECT * FROM products WHERE product_id=:id", [":id" => $txtID]);

    if (!$registro) {
        redirigir_con_mensaje('index.php', 'El registro solicitado no existe.', 'error');
    }

    $product_name = $registro["product_name"];
    $foto_actual = $registro["foto"];
    $model_year = $registro["model_year"];
    $price = $registro["price"];
    $category_id = $registro["category_id"];
} else {
    redirigir_con_mensaje('index.php', 'Debes seleccionar un producto válido.', 'warning');
}

if ($_POST) {
    $txtID = (isset($_POST["txtID"]) && $_POST["txtID"] !== "") ? $_POST["txtID"] : "";

    if ($txtID === '') {
        redirigir_con_mensaje('index.php', 'Debes seleccionar un producto válido.', 'warning');
    }

    $product_name = (isset($_POST["product_name"]) ? $_POST["product_name"] : "");
    $model_year = (isset($_POST["model_year"]) ? $_POST["model_year"] : "");
    $price = (isset($_POST["price"]) ? $_POST["price"] : "");
    $category_id = (isset($_POST["category_id"]) ? $_POST["category_id"] : "");
    $foto = (isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "");

    $registro_recuperado = \DB::getRegistro("SELECT foto FROM products WHERE product_id=:id", [":id" => $txtID]);

    if ($foto !== "") {
        $directorio_imagenes = __DIR__ . "/img/";

        if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] !== "" && file_exists($directorio_imagenes . $registro_recuperado["foto"])) {
            unlink($directorio_imagenes . $registro_recuperado["foto"]);
        }

        $fecha_ = new DateTime();
        $nombreArchivo_foto = $fecha_->getTimestamp() . "_" . $foto;
        $tmp_foto = $_FILES["foto"]["tmp_name"];

        if ($tmp_foto !== "") {
            move_uploaded_file($tmp_foto, $directorio_imagenes . $nombreArchivo_foto);
        }
    } else {
        $nombreArchivo_foto = $registro_recuperado["foto"];
    }

    if ($product_name === "" || $model_year === "" || $price === "" || $category_id === "") {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        \DB::ejecutarConsulta(
            "UPDATE products SET product_name=:product_name, model_year=:model_year, price=:price, category_id=:category_id, foto=:foto WHERE product_id=:id",
            [
                ":id" => $txtID,
                ":product_name" => $product_name,
                ":model_year" => $model_year,
                ":price" => $price,
                ":category_id" => $category_id,
                ":foto" => $nombreArchivo_foto,
            ]
        );
        redirigir_con_mensaje('index.php', 'Registro actualizado');
    }
}

$lista_categorias = \DB::getTabla("SELECT * FROM categories");
$accion = "Actualizar";
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Inventario</span>
        <h1 class="h3 fw-bold mb-1">Editar producto</h1>
        <p class="text-secondary mb-0">Actualiza los datos y conserva la imagen si no la reemplazas.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>
<?php include("../../templates/footer.php"); ?>