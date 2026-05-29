<?php require_once __DIR__ . '/../../bd.php';
app_require_auth();
require_once __DIR__ . '/../../libs/functions.php';
$txtID = '';
$product_name = '';
$imagen_actual = '';
$model_year = '';
$price = '';
$category_id = '';
if (request()->method() === 'POST') {
    $txtID = request()->get("txtID", "");

    if ($txtID === '') {
        redirigir_con_mensaje('index.php', 'Debes seleccionar un producto válido.', 'warning');
    }

    $product_name = request()->get("product_name", "");
    $model_year = request()->get("model_year", "");
    $price = request()->get("price", "");
    $category_id = request()->get("category_id", "");
    $imagen = request()->file("imagen", "name", "");

    $registro_recuperado = \DB::getRegistro("SELECT imagen FROM products WHERE product_id=:id", [":id" => $txtID]);

    if ($imagen !== "") {
        $directorio_imagenes = __DIR__ . "/img/";

        if (isset($registro_recuperado["imagen"]) && $registro_recuperado["imagen"] !== "" && file_exists($directorio_imagenes . $registro_recuperado["imagen"])) {
            unlink($directorio_imagenes . $registro_recuperado["imagen"]);
        }

        $fecha_ = new DateTime();
        $nombreArchivo_imagen = $fecha_->getTimestamp() . "_" . $imagen;
        $tmp_imagen = request()->file("imagen", "tmp_name", "");

        if ($tmp_imagen !== "") {
            move_uploaded_file($tmp_imagen, $directorio_imagenes . $nombreArchivo_imagen);
        }
    } else {
        $nombreArchivo_imagen = $registro_recuperado["imagen"];
    }

    if ($product_name === "" || $model_year === "" || $price === "" || $category_id === "") {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        \DB::ejecutarConsulta(
            "UPDATE products SET product_name=:product_name, model_year=:model_year, price=:price, category_id=:category_id, imagen=:imagen WHERE product_id=:id",
            [
                ":id" => $txtID,
                ":product_name" => $product_name,
                ":model_year" => $model_year,
                ":price" => $price,
                ":category_id" => $category_id,
                ":imagen" => $nombreArchivo_imagen,
            ]
        );
        redirigir_con_mensaje('index.php', 'Registro actualizado');
    }
} else {
    $txtID = (string) request()->get('txtID', '');

    if ($txtID !== '') {
        $registro = \DB::getRegistro("SELECT * FROM products WHERE product_id=:id", [":id" => $txtID]);

        if (!$registro) {
            redirigir_con_mensaje('index.php', 'El registro solicitado no existe.', 'error');
        }

        $product_name = $registro["product_name"];
        $imagen_actual = $registro["imagen"];
        $model_year = $registro["model_year"];
        $price = $registro["price"];
        $category_id = $registro["category_id"];
    } else {
        redirigir_con_mensaje('index.php', 'Debes seleccionar un producto válido.', 'warning');
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