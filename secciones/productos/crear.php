<?php require_once __DIR__ . '/../../bd.php';
if ($_POST) {
    //RECOLECTAMOS LOS DATOS DEL POST
    $product_name = (isset($_POST["product_name"]) ? $_POST["product_name"] : "");
    $model_year = (isset($_POST["model_year"]) ? $_POST["model_year"] : "");
    $price = (isset($_POST["price"]) ? $_POST["price"] : "");
    $category_id = (isset($_POST["category_id"]) ? $_POST["category_id"] : "");
    $foto = (isset($_FILES["foto"]["name"]) ? $_FILES["foto"]["name"] : "");

    if ($product_name === "" || $model_year === "" || $price === "" || $category_id === "") {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        $nombreArchivo_foto = "";

        if ($foto !== "") {
            $fecha_ = new DateTime();
            $nombreArchivo_foto = $fecha_->getTimestamp() . "_" . $foto;
            $tmp_foto = $_FILES["foto"]["tmp_name"];

            if ($tmp_foto !== "") {
                move_uploaded_file($tmp_foto, "./img/" . $nombreArchivo_foto);
            }
        }

        \DB::ejecutarConsulta(
            "INSERT INTO products (product_id, product_name, model_year, price, category_id, foto) VALUES (NULL, :product_name, :model_year, :price, :category_id, :foto)",
            [
                ":product_name" => $product_name,
                ":model_year" => $model_year,
                ":price" => $price,
                ":category_id" => $category_id,
                ":foto" => $nombreArchivo_foto,
            ]
        );
        redirigir_con_mensaje('index.php', 'Registro agregado');
    }
}
//Consulta de categorias para mostrar en el select del formulario
$lista_categorias = \DB::getTabla("SELECT * FROM categories");
$txtID = "";
$product_name = "";
$foto_actual = "";
$model_year = "";
$price = "";
$category_id = "";
$accion = "Agregar";
?>
<?php include("../../templates/header.php"); ?>
<br>
<?php include("form.php"); ?>

<?php include("../../templates/footer.php"); ?>