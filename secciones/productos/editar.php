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
        if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] !== "" && file_exists("./img/" . $registro_recuperado["foto"])) {
            unlink("./img/" . $registro_recuperado["foto"]);
        }

        $fecha_ = new DateTime();
        $nombreArchivo_foto = $fecha_->getTimestamp() . "_" . $foto;
        $tmp_foto = $_FILES["foto"]["tmp_name"];

        if ($tmp_foto !== "") {
            move_uploaded_file($tmp_foto, "./img/" . $nombreArchivo_foto);
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
<br>
<h2>Editar Producto</h2>
<?php include("form.php"); ?>
<?php include("../../templates/footer.php"); ?>