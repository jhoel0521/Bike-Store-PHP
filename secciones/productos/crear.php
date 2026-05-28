<?php require_once __DIR__ . '/../../bd.php';
if (request()->method() === 'POST') {
    //RECOLECTAMOS LOS DATOS DEL POST
    $product_name = request()->get("product_name", "");
    $model_year = request()->get("model_year", "");
    $price = request()->get("price", "");
    $category_id = request()->get("category_id", "");
    $imagen = request()->file("imagen", "name", "");

    if ($product_name === "" || $model_year === "" || $price === "" || $category_id === "") {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        $nombreArchivo_imagen = "";

        if ($imagen !== "") {
            $fecha_ = new DateTime();
            $nombreArchivo_imagen = $fecha_->getTimestamp() . "_" . $imagen;
            $tmp_imagen = request()->file("imagen", "tmp_name", "");

            if ($tmp_imagen !== "") {
                move_uploaded_file($tmp_imagen, "./img/" . $nombreArchivo_imagen);
            }
        }

        \DB::ejecutarConsulta(
            "INSERT INTO products (product_id, product_name, model_year, price, category_id, imagen) VALUES (NULL, :product_name, :model_year, :price, :category_id, :imagen)",
            [
                ":product_name" => $product_name,
                ":model_year" => $model_year,
                ":price" => $price,
                ":category_id" => $category_id,
                ":imagen" => $nombreArchivo_imagen,
            ]
        );
        redirigir_con_mensaje('index.php', 'Registro agregado');
    }
}
//Consulta de categorias para mostrar en el select del formulario
$lista_categorias = \DB::getTabla("SELECT * FROM categories");
$txtID = "";
$product_name = "";
$imagen_actual = "";
$model_year = "";
$price = "";
$category_id = "";
$accion = "Agregar";
?>
<?php include("../../templates/header.php"); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary rounded-pill mb-2">Inventario</span>
        <h1 class="h3 fw-bold mb-1">Nuevo producto</h1>
        <p class="text-secondary mb-0">Registra un producto con imagen, categoría y precio claros.</p>
    </div>
    <a class="btn btn-outline-secondary px-4 rounded-pill" href="index.php" role="button">Volver</a>
</div>
<?php include("form.php"); ?>

<?php include("../../templates/footer.php"); ?>