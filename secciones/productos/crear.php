<?php include("../../bd.php");
if ($_POST) {
    //RECOLECTAMOS LOS DATOS DEL POST
    $product_name = (isset($_POST["product_name"])?$_POST["product_name"]: "");
    $model_year = (isset($_POST["model_year"])?$_POST["model_year"]: "");
    $price = (isset($_POST["price"])?$_POST["price"]: "");
    $category_id = (isset($_POST["category_id"])?$_POST["category_id"]: "");
    //Para la imagen cambiamos a $_FILES y agregamos ['name']
    $foto = (isset($_FILES["foto"]["name"])?$_FILES["foto"]["name"]: "");
    //preparar la insercion de datos
    $sentencia = $conexion->prepare("INSERT INTO 
    products (product_id, product_name, model_year, price, category_id, foto) 
    VALUES (NULL, :product_name, :model_year, :price, :category_id, :foto)");
    //vamos asignar los valores que usan : variables de la sentencia
    $sentencia->bindParam(":product_name", $product_name);
    $sentencia->bindParam(":model_year", $model_year);
    $sentencia->bindParam(":price", $price);
    $sentencia->bindParam(":category_id", $category_id);
    $sentencia->bindParam(":foto", $foto);
    //adjuntar la imagen con nombre distinto de archivo
    $fecha_=new DateTime();
    $nombreArchivo_foto = ($foto='') ? $fecha_->getTimestamp() . "_" . $_FILES["foto"]["name"]:"";
    //creamos archivos temporales para subir las imagenes
    $tmp_foto = $_FILES["foto"]["full_path"];
    if ($tmp_foto != '') {
        move_uploaded_file($tmp_foto, "./img/" . $nombreArchivo_foto);
    }
    $sentencia->bindParam(":foto", $nombreArchivo_foto);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location: index.php?mensaje=" . $mensaje);
}
//Consulta de categorias para mostrar en el select del formulario
$sentencia = $conexion->prepare("SELECT * FROM categories");
$sentencia->execute();
$lista_categorias = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include("../../templates/header.php"); ?>
<br>
<div class="card">
    <div class="card-header">Datos del producto</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Descripci&oacute;n:</label>
                <input type="text" class="form-control" name="product_name" id="product_name"
                    aria-describedby="helpId" placeholder="nombre del producto">
                <small id="helpId" class="form-text text-muted">Ingrese el nombre del producto</small>
            </div>
            <br>
            <div class="mb-3">
                <label for="foto" class="form-label">Imagen del producto</label>
                <input type="file" class="form-control" name="foto" id="foto"
                    aria-describedby="helpId" placeholder="imagen">
                <small id="helpId" class="form-text text-muted">Ingrese el nombre de archvo del producto</small>
            </div>
            <br>
            <div class="mb-3">
                <label for="model_year" class="form-label">DModelo año</label>
                <input type="text" class="form-control" name="model_year" id="model_year"
                    aria-describedby="helpId" placeholder="año del producto">
                <small id="helpId" class="form-text text-muted">Ingrese el año del modelo del producto</small>
            </div>
            <br>
            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="text" class="form-control" name="price" id="price"
                    aria-describedby="helpId" placeholder="precio">
                <small id="helpId" class="form-text text-muted">Ingrese el precio del producto</small>
            </div>
            <br>
            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría:</label>
                <select class="form-selesct form-select-sm=" name="category_id" id="category_id">
                    <option selected="">Seleccione una categoría</option>
                    <?php foreach ($lista_categorias as $registro) { ?>
                        <option value="<?php echo $registro['category_id'] ?>">
                            <?php echo $registro['category_name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-outline-success">Agregar</button>
            <a name="" id="" class="btn btn-outline-secondary" href="index.php" role="button">Cancelar</a>          
        </form>
    </div>
</div>


<?php include("../../templates/footer.php"); ?>